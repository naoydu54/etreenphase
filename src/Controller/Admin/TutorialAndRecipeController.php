<?php

namespace App\Controller\Admin;

use App\Entity\TutorialAndRecipe;
use App\Form\TutorialAndRecipeType;
use App\Services\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tutorial_recipe", name="admin_tutorial_recipe_")
 */

class TutorialAndRecipeController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('Admin/TutorialAndRecipe/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * @Route("/ajaxdata", options={"expose"=true},  name="ajax_data")
     */
    public function ajaxData(EntityManagerInterface $entityManager): Response
    {
        $tutorialAndRecipes = $entityManager->getRepository(TutorialAndRecipe::class)->findAll();
        $datas = [];

        foreach ($tutorialAndRecipes as $tutorialAndRecipe) {
            $datas['data'][] = [
                'id' => $tutorialAndRecipe->getId(),
                'name' => $tutorialAndRecipe->getTitle()
            ];

        }
        return new JsonResponse($datas);

    }

    /**
     * @Route("/add", name="add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager,  FileUploaderService $fileUploader): Response
    {

        $tutorialAndRecipe = new TutorialAndRecipe();
        $form = $this->createForm(TutorialAndRecipeType::class, $tutorialAndRecipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $tutorialAndRecipeImage = $form->get('image')->getData();
            if ($tutorialAndRecipeImage) {
                $tutorialAndRecipeImageName = $fileUploader->upload($tutorialAndRecipeImage, $this->getParameter('kernel.project_dir') . '/public/uploads/tutorialAndRecipe/');
                $tutorialAndRecipe->setImage($tutorialAndRecipeImageName);
            }


            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tutorialAndRecipe);
            $entityManager->flush();

            return $this->redirectToRoute('admin_tutorial_recipe_edit', ['id'=>$tutorialAndRecipe->getId()]);
        }


        return $this->render('Admin/TutorialAndRecipe/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/edit/{id}",options={"expose"=true}, name="edit")
     * @param Request $request
     * @return Response
     */
    public function edit($id, EntityManagerInterface $entityManager, Request  $request,  FileUploaderService $fileUploader)
    {
        $tutorialAndRecipe = $entityManager->getRepository(TutorialAndRecipe::class)->find($id);

        $form = $this->createForm(TutorialAndRecipeType::class, $tutorialAndRecipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $tutorialAndRecipeImage = $form->get('image')->getData();
            if ($tutorialAndRecipeImage) {
                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('kernel.project_dir') . '/public/uploads/tutorialAndRecipe/'.$tutorialAndRecipe->getImage());
                $tutorialAndRecipeImageName = $fileUploader->upload($tutorialAndRecipeImage, $this->getParameter('kernel.project_dir') . '/public/uploads/tutorialAndRecipe/');
                $tutorialAndRecipe->setImage($tutorialAndRecipeImageName);
            }



            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tutorialAndRecipe);
            $entityManager->flush();

            return $this->redirectToRoute('admin_tutorial_recipe_edit', ['id'=>$tutorialAndRecipe->getId()]);
        }


        return $this->render('Admin/TutorialAndRecipe/add.html.twig', [
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
        $page = $entityManager->getRepository(TutorialAndRecipe::class)->find($id);

        $entityManager->remove($page);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }
}
