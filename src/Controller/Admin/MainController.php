<?php

namespace App\Controller\Admin;

use App\Entity\Actuality;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {


        return $this->render('Admin/Main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }



}
