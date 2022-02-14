<?php

namespace App\Controller\Admin;

use App\Entity\Attribute;
use App\Entity\Combination;
use App\Entity\File;
use App\Entity\Menu;
use App\Entity\Page;
use App\Entity\Product;
use App\Entity\ProductHasAttributeItem;
use App\Form\CombinationType;
use App\Form\ProductHasAttributeType;
use App\Form\ProductType;
use App\Services\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @Route("/admin/product", name="admin_product_")
 */
class ProductController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repo = $entityManager->getRepository(Menu::class);

        $test = $entityManager->getRepository('App:AttributeItem')->createQueryBuilder('a')
            ->innerJoin('a.attribute', 'a2')
            ->getQuery()
            ->getResult();


        return $this->render('Admin/Product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/ajaxdata", options={"expose"=true},  name="ajax_data")
     */
    public function ajaxData(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();
        $datas = [];
        foreach ($products as $product) {
            $menuParent = '';

            if ($product->getMenus()->first()->getParent()) {
                $menuParent = $product->getMenus()->first()->getParent()->getName() . ' - ';

            }

            $checkedIncontournable =null;
            if($product->getIncontournable() == 1){
                $checkedIncontournable = "checked";
            }

            $datas['data'][] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'reference' => $product->getReference(),
                'configurable' => $product->getConfigurable(),
                'menu' => '<b>' . $menuParent . '  </b> ' . $product->getMenus()->first()->getName(),
                'incontournable' => $checkedIncontournable,

            ];

        }
        return new JsonResponse($datas);

    }

    /**
     * @Route("/add/{configurable}", name="add")
     * @param Request $request
     * @return Response
     */
    public function add($configurable, Request $request, EntityManagerInterface $entityManager, FileUploaderService $fileUploader, string $projectDir): Response
    {

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, [
            'action' => $this->generateUrl('admin_product_add', ['configurable' => $configurable])
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($configurable == 1) {

                $product->setConfigurable(1);
            } else {
                $product->setConfigurable(0);

            }
            /** @var UploadedFile $productFile */
            $productImage = $form->get('image')->getData();
            $productFiles = $form->get('files')->getData();



            if ($productImage) {
                $productImageName = $fileUploader->upload($productImage, $projectDir . '/public/uploads/product/'.$product->getId().'/');
                $product->setImage($productImageName);
            }

            if (count($productFiles)> 0 ){
                foreach ($productFiles as $productFile){
                    $file = new File();
                    $file->addProduct($product);
                    $productFileName = $fileUploader->upload($productImage, $projectDir . '/public/uploads/product/'.$product->getId().'/');

                    $file->setName($productFileName);

                    $product->setImage($productFileName);

                    $product->setImage($productFileName);
                    $product->addFile($file);

                    $entityManager->persist($product);
                    $entityManager->persist($file);
                }
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('admin_product_main');
        }


        return $this->render('Admin/Product/add.html.twig', [
            'form' => $form->createView(),
            'configurable' => $configurable

        ]);
    }

    /**
     * @Route("/edit/{id}/", name="edit", options={"expose"=true})
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager, FileUploaderService $fileUploader, string $projectDir): Response
    {

        $product = $entityManager->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);

        $configurable = $product->getConfigurable();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $productImage = $form->get('image')->getData();
            $productFiles = $form->get('files')->getData();


            if ($productImage) {

                $filesystem = new Filesystem();
                $filesystem->remove("$projectDir/public/uploads/product/{$product->getId()}/{$product->getImage()}");
                $productImageName = $fileUploader->upload($productImage, $projectDir . '/public/uploads/product/'.$product->getId().'/');
                $product->setImage($productImageName);
            }


            if (count($productFiles)> 0 ){

                if(count($product->getFiles())){
                    /** @var File $file */
                    foreach ($product->getFiles() as $item) {
                        $filesystem = new Filesystem();
                        $filesystem->remove($projectDir . '/public/uploads/product/'.$product->getId().'/'.$item->getName());
                        $product->removeFile($item);

                        $entityManager->persist($product);
                        $entityManager->flush();
                    }
                }


                foreach ($productFiles as $productFile){

                    $file = new File();
                    $file->addProduct($product);

                    $productFileName = $fileUploader->upload($productFile, $projectDir . '/public/uploads/product/'.$product->getId().'/');
                    $file->setName($productFileName);


                    $product->addFile($file);

                    $entityManager->persist($product);
                    $entityManager->persist($file);
                }
            }


            $entityManager->flush();

            return $this->redirectToRoute('admin_product_main');
        }


        return $this->render('Admin/Product/add.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'configurable' => $configurable
        ]);
    }


    /**
     * @Route("/remove/{id}",options={"expose"=true}, name="remove")
     * @param Request $request
     * @return Response
     */
    public function remove($id, EntityManagerInterface $entityManager)
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        $entityManager->remove($product);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }


    /**
     * @Route("/duplicate/{id}",options={"expose"=true}, name="duplicate")
     * @param Request $request
     * @return Response
     */
    public function duplicate($id, EntityManagerInterface $entityManager)
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        $duplicateProduct = new Product();
        $duplicateProduct->setName($product->getName() . 'Copie');
        $duplicateProduct->setContent($product->getContent());
        $duplicateProduct->setReference($product->getReference());
        $duplicateProduct->setPriceCeTTC($product->getPriceCeTTC());
        $duplicateProduct->setPricePublicTTC($product->getPricePublicTTC());
        $duplicateProduct->setConfigurable($product->getConfigurable());
        $duplicateProduct->addMenu($product->getMenus()->first());

        $duplicateAttributProduct = new ProductHasAttributeItem();
        foreach ($product->getProductHasAttributeItems() as $productHasAttributeItem) {
            $duplicateAttributProduct->setProduct($duplicateProduct);
            $duplicateAttributProduct->setAttributeItem($productHasAttributeItem->getAttributeItem());
            $entityManager->persist($duplicateAttributProduct);

        }


        $entityManager->persist($duplicateProduct);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }

    /**
     * @Route("/combination/{productId}",options={"expose"=true}, name="combination")
     * @param Request $request
     * @return Response
     */
    public function combination($productId, Request $request, EntityManagerInterface $entityManager)
    {


        $combinations = $entityManager->getRepository(Combination::class)->createQueryBuilder('c')
            ->addSelect('ca')
            ->innerJoin('c.attributeItem', 'ca')
            //->innerJoin('ca.attribute', 'a')
            ->innerJoin('c.product', 'p')
            ->where('p.id = :productId')
            ->setParameter('productId', $productId)
            ->getQuery()
            ->getResult();

        $groupedCombinations = [];
        /** @var Combination $combination */
        foreach ($combinations as $combination) {
            $attributeName = $combination->getAttributeItem()->getAttribute()->getName();
            if (false === isset($groupedCombinations[$attributeName])) {
                $groupedCombinations[$attributeName] = [];
            }
            $groupedCombinations[$attributeName][] = $combination->getAttributeItem()->getValue();
        }


        $combination = new Combination();


        $form = $this->createForm(CombinationType::class, $combination, [
            'action' => $this->generateUrl('admin_product_combination', ['productId' => $productId])
        ]);
        $product = $entityManager->getRepository(Product::class)->find($productId);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($combination);
            $entityManager->flush();

            $combination->setProduct($product);
            $entityManager->persist($combination);
            $entityManager->flush();

            return $this->redirectToRoute('admin_product_main');

        }


        return $this->render('Admin/Product/combination.html.twig', [
            'form' => $form->createView(),
            'groupedCombinations' => $groupedCombinations

        ]);

    }

    /**
     * @Route("/incontournable/",options={"expose"=true}, name="incontournable")
     * @param Request $request
     * @return Response
     */
    public function incontournable()
    {

        return $this->render('Admin/Product/incontournable.html.twig', [


        ]);

    }


    /**
     * @Route("/update_incontournable/{data}",options={"expose"=true}, name="update_incontournable")
     * @param Request $request
     * @return Response
     */
    public function updateProductIncontournable($data, EntityManagerInterface $entityManager)
    {
        $productIncontournables = $entityManager->getRepository(Product::class)->findBy(['incontournable' => 1]);

        foreach ($productIncontournables as $productIncontournable) {
            $productIncontournable->setIncontournable(0);
            $entityManager->persist($productIncontournable);

        }
        $entityManager->flush();


        $dataIdproduct = explode(',', $data);

        /** @var Product $product */
        foreach ($dataIdproduct as $productId) {
            $product = $entityManager->getRepository(Product::class)->find($productId);
            $product->setIncontournable(1);
            $entityManager->persist($product);
        }
        $entityManager->flush();
        $r = ['success' => 'success'];
        return new JsonResponse($r);

    }

}
