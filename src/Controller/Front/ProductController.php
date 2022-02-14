<?php

namespace App\Controller\Front;

use App\Entity\Attribute;
use App\Entity\AttributeItem;
use App\Entity\Combination;
use App\Entity\Menu;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_")
 */
class ProductController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('/Front/Product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/product_menu/{id}", name="product_by_menu")
     */
    public function productByMenu(EntityManagerInterface $entityManager, $id)
    {

        $menu = $entityManager->getRepository(Menu::class)->find($id);
//        $products = $entityManager->getRepository(Combination::class)->createQueryBuilder('c')
//            ->select(' MAX(c.priceCeTTC) as priceMax', 'MIN(c.priceCeTTC) priceMin')
//            ->innerJoin('c.product', 'cp')
//            ->innerJoin('cp.menus', 'cpm')
//            ->where('cpm.id = :menuId')
//            ->setParameter('menuId', $id)
//            ->getQuery()
//            ->getResult();
        $products =  $entityManager->getRepository(Product::class)->createQueryBuilder('p')
            ->select(' MAX(pc.priceCeTTC) as priceMax', 'MIN(pc.priceCeTTC) priceMin')

            ->innerJoin('p.combinations', 'pc')
            ->innerJoin('p.menus', 'pm')
            ->where('pm.id = :menuId')
            ->setParameter('menuId', $id)
            ->getQuery()
            ->getResult();

        if ($products['0']['priceMax'] == null){
            $products =  $entityManager->getRepository(Product::class)->createQueryBuilder('p')
                ->select(' MAX(p.priceCeTTC) as priceMax', 'MIN(p.priceCeTTC) priceMin')
                ->innerJoin('p.menus', 'pm')
                ->where('pm.id = :menuId')
                ->setParameter('menuId', $id)
                ->getQuery()
                ->getResult();
        }
        $productsForFiltres = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
            ->innerJoin('p.combinations','pc')
            ->innerJoin('p.menus', 'pm')
            ->where('pm.id = :menuId')
            ->setParameter('menuId',$id)
            ->getQuery()
            ->getResult();

        $dataFiltre =[];
        foreach ($productsForFiltres as $productstest) {
            foreach ($productstest->getCombinations() as $combination) {
                if (!array_key_exists($combination->getAttributeItem()->getAttribute()->getName(), $dataFiltre)) {
                    $dataFiltre[$combination->getAttributeItem()->getAttribute()->getName()][] = $combination->getAttributeItem()->getValue();

                }else{
                    if (!in_array($combination->getAttributeItem()->getValue(), $dataFiltre[$combination->getAttributeItem()->getAttribute()->getName()])){
                        $dataFiltre[$combination->getAttributeItem()->getAttribute()->getName()][] = $combination->getAttributeItem()->getValue();

                    }

                }


            }
        }



        return $this->render('Front/Product/productByMenu.html.twig', [
            'products' => $products,
            'menu' => $menu,
            'dataFiltre'=>$dataFiltre

        ]);

    }

    /**
     * @Route("/{id}", name="view")
     */
    public function product(Product $product, EntityManagerInterface $entityManager)
    {

        $relatedProducts = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
            ->innerJoin('p.menus', 'pm')
            ->where('pm = :menu')
            ->setParameter('menu', $product->getMenus()->first())
            ->setMaxResults('4')
            ->getQuery()
            ->getResult();

        return $this->render('Front/Product/product.html.twig', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);

    }


    /**
     * @Route("/_menu_ajax/{id}", options={"expose"=true}, name="by_menu_ajax")
     */
    public function productAjaxByMenu(Request $request, EntityManagerInterface $entityManager, $id)
    {
        $data = [];

        if (!empty($request->request->get('data'))){


            if (isset( $request->request->get('data')['minValue'])){
                $minValuePrice = intval($request->request->get('data')['minValue']);

            }
            if (isset( $request->request->get('data')['maxValue'])){
                $maxValuePrice = intval($request->request->get('data')['maxValue']);

            }

            if (isset( $request->request->get('data')['dataOtherFiltres'])){
                $dataOtherFiltres = $request->request->get('data')['dataOtherFiltres'];

            }else{
                $dataOtherFiltres=null;
            }
        }
        else{
            $dataOtherFiltres=null;

        }





        $menu = $entityManager->getRepository(Menu::class)->find($id);

        $qb = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
            ->innerJoin('p.menus', 'pm')
            ->leftJoin('p.combinations', 'cp')
            ->leftJoin('cp.attributeItem','cpa');

        if ($request->request->get('data') !== null) {
            if (isset($minValuePrice) && isset($maxValuePrice)){
                $qb->andWhere('cp.priceCeTTC BETWEEN :minValuePrice AND :maxValuePrice ')
                    ->setParameter('minValuePrice', $minValuePrice)
                    ->setParameter('maxValuePrice', $maxValuePrice)
                    ->orWhere('p.priceCeTTC BETWEEN :minValuePrice AND :maxValuePrice ')
                    ->setParameter('minValuePrice', $minValuePrice)
                    ->setParameter('maxValuePrice', $maxValuePrice);
            }

        }
        if ( $dataOtherFiltres !== null){
            foreach ($dataOtherFiltres as $otherFiltre){
                $qb->andWhere('cpa.value LIKE :filtre')
                    ->setParameter(':filtre', $otherFiltre)


                ;
            }
        }
        $qb->Andwhere('pm.id = :menuId')
        ->setParameter('menuId', $id);
        $products = $qb->getQuery()->getResult();


        /** @var Product $product */
        foreach ($products as $product) {
            $data[] = ['id' => $product->getId(), 'image' => $product->getImage(), 'name' => $product->getName()];
        }

        return $this->render('Front/Product/productByMenuAjax.html.twig', [
            'products' => $products,
            'menu' => $menu

        ]);


    }
}
