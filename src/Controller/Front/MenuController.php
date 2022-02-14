<?php

namespace App\Controller\Front;

use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menu", name="menu_")
 */
class MenuController extends AbstractController
{

    /**
     * @Route("/menu", name="main")
     */
    public function index(EntityManagerInterface  $entityManager): Response
    {

        if ($this->getUser()){
            if ($this->isGranted('ROLE_ADMIN')){
                $menus = $entityManager->getRepository(Menu::class)->findAll();
            }else{
                $companyId = $this->getUser()->getCompany()->getId();

                $menus = $entityManager->getRepository(Menu::class)->createQueryBuilder('m')

                    ->leftJoin('m.companies', 'mc2')
                    ->where('mc2.id = :companyId' )
                    ->setParameter('companyId', $companyId)
                    ->orWhere('m.defaultMenu = :defaultMenu')
                    ->setParameter('defaultMenu', 1)

                    ->getQuery()
                    ->getResult();
            }

        }else{
            $menus = $entityManager->getRepository(Menu::class)->findBy(['defaultMenu'=>1]);

        }



        return $this->render('Front/Menu/index.html.twig', [
            'controller_name' => 'MenuController',
            'menus'=>$menus
        ]);
    }
}
