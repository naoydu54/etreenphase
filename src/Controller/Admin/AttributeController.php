<?php

namespace App\Controller\Admin;

use App\Entity\Attribute;
use App\Entity\AttributeItem;
use App\Form\AttributeType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/attribute", name="admin_attribute_")
 */
class AttributeController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('Admin/Attribute/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/ajaxdata", options={"expose"=true},  name="ajax_data")
     */
    public function ajaxData(EntityManagerInterface $entityManager): Response
    {
        $attributes = $entityManager->getRepository(Attribute::class)->findAll();
        $datas = [];

        foreach ($attributes as $attribute) {
            $datas['data'][] = [
                'id' => $attribute->getId(),
                'name' => $attribute->getName()
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

        $attribute = new Attribute();



        $form = $this->createForm(AttributeType::class, $attribute);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $attribute = $form->getData();

            $entityManager->persist($attribute);
            $entityManager->flush();

            return $this->redirectToRoute('admin_attribute_edit', ['id'=>$attribute->getId()]);
        }


        return $this->render('Admin/Attribute/add.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", options={"expose"=true})
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $attribute = $entityManager->getRepository(Attribute::class)->find($id);
        $attributeItems = new ArrayCollection();
        foreach ($attribute->getAttributeItems() as $attributeItem) {
            $attributeItems->add($attributeItem);
        }
        $form = $this->createForm(AttributeType::class, $attribute);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->$attribute()->getManager();
            $entityManager->persist($attribute);
            $entityManager->flush();

            return $this->redirectToRoute('admin_attribute_edit', ['id'=>$attribute->getId()]);
        }


        return $this->render('Admin/Attribute/add.html.twig', [
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
        $attribute = $entityManager->getRepository(Attribute::class)->find($id);

        $entityManager->remove($attribute);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }
}
