<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/setting", name="admin_setting_")
 */
class SettingController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('Admin/Setting/index.html.twig', [
            'controller_name' => 'SettingController',
        ]);
    }

    /**
     * @Route("/maintenance", options={"expose"=true}, name="maintenance")
     */
    public function maintenance(Request  $request, EntityManagerInterface $entityManager){

        $maintenance = $entityManager->getRepository(Setting::class)->findOneBy(['name'=>'maintenance']);

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_setting_maintenance'))


            ->add('maintenance', ChoiceType::class, [

                'attr'=>['class'=>'maintenance'],
                'choices'  => [
                    'On' => 'true',
                    'Off' => 'false',
                ],


                'label' => 'Maintenance'
            ])


            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys




            $maintenance->setValue($form->getData()['maintenance']);
            $entityManager->persist($maintenance);
            $entityManager->flush();

                return $this->redirectToRoute('admin_setting_main');

            }

        return $this->render('Admin/Setting/maintenance.html.twig', [
            'form' => $form->createView(),
            'maintenanceVal'=>$maintenance
        ]);


    }


}
