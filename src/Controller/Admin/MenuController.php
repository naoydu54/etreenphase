<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Form\MenuTypeOld;
use App\Services\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/menu", name="admin_menu_")
 */

class MenuController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('Admin/Menu/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * @Route("/ajaxdata", options={"expose"=true},  name="ajax_data")
     */
    public function ajaxData(EntityManagerInterface $entityManager): Response
    {
        $menus = $entityManager->getRepository(Menu::class)->findAll();
        $datas = [];

        foreach ($menus as $menu) {
            $datas['data'][] = [
                'id' => $menu->getId(),
                'name' => $menu->getName()
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

        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $menuFile = $form->get('image')->getData();
            if ($menuFile) {
                $productImageName = $fileUploader->upload($menuFile, $this->getParameter('kernel.project_dir').'/public/uploads/menu/');
                $menu->setImage($productImageName);
            }

            $entityManager->persist($menu);
            $entityManager->flush();

            return $this->redirectToRoute('admin_menu_edit', ['id'=>$menu->getId()]);
        }


        return $this->render('Admin/Menu/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/edit/{id}",options={"expose"=true}, name="edit")
     * @param Request $request
     * @return Response
     */
    public function edit($id, EntityManagerInterface $entityManager, Request  $request,  FileUploaderService $fileUploader )
    {
        $menu = $entityManager->getRepository(Menu::class)->find($id);

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();
            $menuFile = $form->get('image')->getData();
            if ($menuFile) {
                $productImageName = $fileUploader->upload($menuFile, $this->getParameter('kernel.project_dir').'/public/uploads/menu/');
                $menu->setImage($productImageName);
            }


            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($menu);
            $entityManager->flush();

            return $this->redirectToRoute('admin_menu_edit', ['id'=>$menu->getId()]);
        }


        return $this->render('Admin/Menu/add.html.twig', [
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
        $menu = $entityManager->getRepository(Menu::class)->find($id);

        $entityManager->remove($menu);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }
}
