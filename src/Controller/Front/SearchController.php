<?php

namespace App\Controller\Front;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search", name="search_")
 */

class SearchController extends AbstractController
{
    /**
     * @Route("/", name="ajax",  options={"expose"=true})
     */
    public function index( Request $request, EntityManagerInterface $entityManager): Response
    {

        $defaultData = ['message' => 'Type your message here'];

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('search_ajax'))

            ->add('search', TextType::class,[
                'attr'=>['class'=>'form-control mr-sm-2', 'placeholder'=>'Rechercher'],
                'label'=>false,

            ])

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $search = $form->getData()['search'];
            $products = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
                ->where('p.name LIKE :search')
                ->setParameter('search', '%'.$search.'%')
                ->getQuery()
                ->getResult();


            if( empty($products)){
                $products = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
                    ->innerJoin('p.combinations', 'pc')
                    ->where('p.reference LIKE :search')
                    ->setParameter('search', $search)
                    ->orWhere('pc.reference LIKE :search')
                    ->setParameter('search', $search)
                    ->getQuery()
                    ->getResult();
            }

            return $this->render('Front/Search/view.html.twig', [
                'products'=>$products
            ]);
        }

        return $this->render('Front/Search/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }




}
