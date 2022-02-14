<?php

namespace App\Controller\Front;

use App\Entity\Product;
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

/**
 * @Route("/tutorial_and_recipe", name="tutorial_and_recipe_")
 */

class TutorialAndRecipeController extends AbstractController
{



    /**
     * @Route("/", name="main")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $productForTutorialAndRecipes = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
        ->innerJoin('p.tutorialAndRecipes', 'pt')
        ->getQuery()
            ->getResult()
        ;

        return $this->render('Front/TutorialAndRecipe/index.html.twig',[
            'productForTutorialAndRecipes'=>$productForTutorialAndRecipes
        ]);
    }

    /**
     * @Route("/ajax", options={"expose"=true},  name="ajax")
     */
    public function ajax(Request $request, EntityManagerInterface $entityManager)
    {


        if (!empty($request->request->get('data'))){

            if (isset( $request->request->get('data')['sugar'])){
                $sugar = $request->request->get('data')['sugar'];

            }

            if (isset( $request->request->get('data')['tutorial'])){
                $tutorial = $request->request->get('data')['tutorial'];


            }
            if (isset( $request->request->get('data')['products'])){
                $products = $request->request->get('data')['products'];




            }

            $qb = $entityManager->getRepository(TutorialAndRecipe::class)->createQueryBuilder('t');

            if (isset($sugar)){
                if ($sugar =='true'){
                    $qb->andWhere('t.sugar = :sugar')
                        ->setParameter('sugar', true);
                }elseif ($sugar=='false'){
                    $qb->andWhere('t.sugar = :sugar')
                        ->setParameter('sugar', false);
                }
            }

            if (isset($tutorial)){
                if ($tutorial =='true'){
                    $qb->andWhere('t.tutorial = :tutorial')
                        ->setParameter('tutorial', true);
                }elseif ($tutorial=='false'){
                    $qb->andWhere('t.tutorial = :tutorial')
                        ->setParameter('tutorial', false);
                }
            }

            if(isset($products)){
                $qb->innerJoin('t.products', 'tp');

                foreach ($products as $product){

                    $product = intval($product);


                    $qb->orWhere('tp.id = :tutorial')
                        ->setParameter('tutorial', $product);
                }
            }


            $tutorialsAndRecipes =$qb->getQuery()->getResult();

        }else{
            $tutorialsAndRecipes = $entityManager->getRepository(TutorialAndRecipe::class)->findAll();

        }
        return $this->render('Front/TutorialAndRecipe/ajax.html.twig', [
            'tutorialsAndRecipes' => $tutorialsAndRecipes,

        ]);

    }


}
