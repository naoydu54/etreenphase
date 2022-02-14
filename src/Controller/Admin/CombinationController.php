<?php

namespace App\Controller\Admin;

use App\Entity\Combination;
use App\Form\CombinationType;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/combination", name="admin_combination_")
 */

class CombinationController extends AbstractController
{
    /**
     * @Route("/{productId}", options={"expose"=true}, name="main")
     */
    public function index($productId): Response
    {
        return $this->render('Admin/Combination/index.html.twig', [
            'controller_name' => 'PageController',
            'productId' => $productId,

        ]);
    }

    /**
     * @Route("/ajaxdata/{productId}", options={"expose"=true},  name="ajax_data")
     */
    public function ajaxData(EntityManagerInterface $entityManager, $productId): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($productId);
        $combiantions = $entityManager->getRepository(Combination::class)->findBy(['product'=>$productId]);
        $datas = [];

        foreach ($combiantions as $combiantion) {
            $datas['data'][] = [
                'id' => $combiantion->getId(),
                'name' => $combiantion->getAttributeItem()->getAttribute()->getName(),
                'value' => $combiantion->getAttributeItem()->getValue(),
                'price'=>$combiantion->getPriceCeTTC(),
                'reference'=>$combiantion->getReference()
            ];

        }
        return new JsonResponse($datas);

    }

    /**
     * @Route("/add/{productId}", name="add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager, $productId): Response
    {

        $product = $entityManager->getRepository(Product::class)->find($productId);

        $combination = new Combination();
        $form = $this->createForm(CombinationType::class, $combination);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            $combination->setProduct($product);
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($combination);
            $entityManager->flush();

            return $this->redirectToRoute('admin_combination_main', ['productId'=>$productId]);
        }


        return $this->render('Admin/Combination/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),
            'product'=>$product

        ]);
    }

    /**
     * @Route("/edit/{combinationId}/{productId}",options={"expose"=true}, name="edit")
     * @param Request $request
     * @return Response
     */
    public function edit($combinationId,$productId, EntityManagerInterface $entityManager, Request  $request)
    {
        $combination = $entityManager->getRepository(Combination::class)->find($combinationId);
        $product = $entityManager->getRepository(Product::class)->find($productId);

        $form = $this->createForm(CombinationType::class, $combination);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($combination);
            $entityManager->flush();

            return $this->redirectToRoute('admin_combination_main', ['productId'=>$productId]);
        }


        return $this->render('Admin/Combination/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),
            'product'=>$product


        ]);

    }

    /**
     * @Route("/remove/{combinationId}",options={"expose"=true}, name="remove")
     * @param Request $request
     * @return Response
     */
    public function remove($combinationId,  EntityManagerInterface $entityManager)
    {
        $combination = $entityManager->getRepository(Combination::class)->find($combinationId);

        $entityManager->remove($combination);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }
}
