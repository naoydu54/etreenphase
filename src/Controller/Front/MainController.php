<?php

namespace App\Controller\Front;


use App\Entity\Actuality;
use App\Entity\Attribute;
use App\Entity\AttributeItem;
use App\Entity\Page;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Company;

use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/")
 */
class MainController extends AbstractController
{
    private $passwordEncoder;
    private $mailer;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="main")
     */
    public function index(EntityManagerInterface  $entityManager): Response
    {

        $campagne = $entityManager->getRepository(Actuality::class)->createQueryBuilder('c')
            ->innerJoin('c.actualityHasProducts', 'ca')
            ->innerJoin('ca.product','cap')
            ->where('c.dateStart <= :dateStart')
            ->setParameter('dateStart', new \DateTime('now'))
            ->andWhere('c.dateEnd >= :dateEnd')
            ->setParameter('dateEnd', new \DateTime('now'))
            ->getQuery()
            ->getOneOrNullResult();

        $incontournables = $entityManager->getRepository(Product::class)->findBy(['incontournable'=>1]);





        return $this->render('Front/Main/index.html.twig', [
            'controller_name' => 'MainController',
            'campagne'=>$campagne,
            'incontournables'=>$incontournables,
        ]);
    }

    /**
     * @Route("/footer", name="footer")
     */
    public function footer(EntityManagerInterface  $entityManager)
    {

        $footer = $entityManager->getRepository(Page::class)->findOneBy(['name'=>'footer']);


        return $this->render('Front/Main/footer.html.twig', [
            'footer'=>$footer

        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function home(EntityManagerInterface  $entityManager)
    {

        $home = $entityManager->getRepository(Page::class)->findOneBy(['name'=>'accueil']);


        return $this->render('Front/Main/home.html.twig', [
            'home'=>$home

        ]);
    }

    /**
     * @Route("/maintenance", name="maintenance")
     */
    public function maintenance()
    {

        return $this->render('Front/Main/maintenance.html.twig');

    }

}
