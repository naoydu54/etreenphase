<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Form\PageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/page", name="admin_page_")
 */
class PageController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('Admin/Page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * @Route("/ajaxdata", options={"expose"=true},  name="ajax_data")
     */
    public function ajaxData(EntityManagerInterface $entityManager): Response
    {
        $pages = $entityManager->getRepository(Page::class)->findAll();
        $datas = [];

        foreach ($pages as $page) {
            $datas['data'][] = [
                'id' => $page->getId(),
                'name' => $page->getName()
            ];

        }
        return new JsonResponse($datas);

    }

    /**
     * @Route("/add", name="add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {

        $page = new Page();
        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('admin_page_edit', ['id'=>$page->getId()]);
        }


        return $this->render('Admin/Page/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/edit/{id}",options={"expose"=true}, name="edit")
     * @param Request $request
     * @return Response
     */
    public function edit($id, EntityManagerInterface $entityManager, Request  $request)
    {
        $page = $entityManager->getRepository(Page::class)->find($id);

        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('admin_page_edit', ['id'=>$page->getId()]);
        }


        return $this->render('Admin/Page/add.html.twig', [
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
        $page = $entityManager->getRepository(Page::class)->find($id);

        $entityManager->remove($page);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }
}
