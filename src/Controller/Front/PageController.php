<?php

namespace App\Controller\Front;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="page_")
 */
class PageController extends AbstractController
{
    /**
     * @Route("/{id}", name="view")
     */
    public function index(Page $page): Response
    {

        return $this->render('Front/Page/index.html.twig', [
            'page' => $page
        ]);
    }
}
