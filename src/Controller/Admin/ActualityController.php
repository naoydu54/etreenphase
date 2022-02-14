<?php

namespace App\Controller\Admin;

use App\Entity\Actuality;
use App\Entity\ActualityHasProduct;
use App\Entity\Page;
use App\Entity\Product;
use App\Form\ActualityType;
use App\Form\PageType;
use App\Services\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/actuality", name="admin_actuality_")
 */
class ActualityController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('Admin/Actuality/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * @Route("/ajaxdata", options={"expose"=true},  name="ajax_data")
     */
    public function ajaxData(EntityManagerInterface $entityManager): Response
    {
        $actualities = $entityManager->getRepository(Actuality::class)->findAll();
        $datas = [];

        foreach ($actualities as $actuality) {
            $datas['data'][] = [
                'id' => $actuality->getId(),
                'title' => $actuality->getTitle(),
                'dateStart' => $actuality->getDateStart()->format('d/m/Y'),
                'dateEnd' => $actuality->getDateEnd()->format('d/m/Y'),
            ];

        }
        return new JsonResponse($datas);

    }

    /**
     * @Route("/add", name="add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager, FileUploaderService $fileUploader): Response
    {

        $actuality = new Actuality();
        $form = $this->createForm(ActualityType::class, $actuality);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            /** @var UploadedFile $actualityFile */
            $actualityFile = $form->get('image')->getData();
            if ($actualityFile) {
                $actualityImageName = $fileUploader->upload($actualityFile, $this->getParameter('kernel.project_dir') . '/public/uploads/actuality/');
                $actuality->setImage($actualityImageName);
            }


            $entityManager->persist($actuality);
            $entityManager->flush();

            return $this->redirectToRoute('admin_actuality_edit', ['id' => $actuality->getId()]);
        }


        return $this->render('Admin/Actuality/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/edit/{id}",options={"expose"=true}, name="edit")
     * @param Request $request
     * @return Response
     */
    public function edit($id, EntityManagerInterface $entityManager, Request $request, FileUploaderService $fileUploader)
    {
        $actuality = $entityManager->getRepository(Actuality::class)->find($id);

        $form = $this->createForm(ActualityType::class, $actuality);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $actualityFile */
            $actualityFile = $form->get('image')->getData();
            if ($actualityFile) {
                $actualityImageName = $fileUploader->upload($actualityFile, $this->getParameter('kernel.project_dir') . '/public/uploads/actuality/');
                $actuality->setImage($actualityImageName);
            }
            $entityManager->persist($actuality);
            $entityManager->flush();

            return $this->redirectToRoute('admin_actuality_edit', ['id' => $actuality->getId()]);
        }


        return $this->render('Admin/Actuality/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),

        ]);

    }

    /**
     * @Route("/remove/{id}",options={"expose"=true}, name="remove")
     * @param Request $request
     * @return Response
     */
    public function remove($id, EntityManagerInterface $entityManager)
    {
        $actuality = $entityManager->getRepository(Actuality::class)->find($id);

        $entityManager->remove($actuality);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }

    /**
     * @Route("/product_actuality/{actualityId}" ,options={"expose"=true}, name="product")
     */
    public function productActuality($actualityId): Response
    {
        return $this->render('Admin/Actuality/productActuality.html.twig', [
            'controller_name' => 'PageController',
            'actualityId' => $actualityId
        ]);
    }


    /**
     * @Route("/ajaxdataproduct/{actualityId}", options={"expose"=true},  name="ajax_data_product")
     */
    public function ajaxDataProduct(EntityManagerInterface $entityManager, $actualityId): Response
    {
        $products = $entityManager->getRepository(Product::class)->createQueryBuilder('a')
            ->leftJoin('a.actualityHasProducts', 'a2')
            ->leftJoin('a2.product', 'a2p')
            ->getQuery()
            ->getResult();;
        $datas = [];
        /** @var Product $product */
        foreach ($products as $product) {
            $checkedIncontournable = null;

            foreach ($product->getActualityHasProducts() as $item) {
                if ($product->getId() == $item->getProduct()->getId()) {
                    $checkedIncontournable = "checked";
                }
            }
            $menuParent = '';

            if ($product->getMenus()->first()->getParent()) {
                $menuParent = $product->getMenus()->first()->getParent()->getName() . ' - ';

            }


            $datas['data'][] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'configurable' => $product->getConfigurable(),
                'menu' => '<b>' . $menuParent . '  </b> ' . $product->getMenus()->first()->getName(),
                'incontournable' => $checkedIncontournable,

            ];

        }
        return new JsonResponse($datas);

    }


    /**
     * @Route("/update_product_actuality/{data}/{actualityId}",options={"expose"=true}, name="update_product")
     * @param Request $request
     * @return Response
     */
    public function updateProductActuality($data, $actualityId, EntityManagerInterface $entityManager)
    {


        $actualityHasProducts = $entityManager->getRepository(ActualityHasProduct::class)->findAll();

        foreach ($actualityHasProducts as $productHasActuality) {
            $entityManager->remove($productHasActuality);
            $entityManager->flush();

        }


        $actuality = $entityManager->getRepository(Actuality::class)->find($actualityId);
        $dataIdproduct = explode(',', $data);



        /** @var Product $product */
        foreach ($dataIdproduct as $productId) {
            $product = $entityManager->getRepository(Product::class)->find($productId);
            $actualityHasProduct = new ActualityHasProduct();
            $actualityHasProduct->setProduct($product);
            $actualityHasProduct->setActuality($actuality);
            $entityManager->persist($actualityHasProduct);
        }
        $entityManager->flush();
        $r = ['success' => 'success'];
        return new JsonResponse($r);

    }
}
