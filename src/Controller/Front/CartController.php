<?php

namespace App\Controller\Front;

use App\Entity\Attribute;
use App\Entity\Cart;
use App\Entity\CartElement;
use App\Entity\Combination;
use App\Entity\CompanyHasAddress;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Form\CartElementType;
use App\Form\CartType;
use App\Services\CartService;
use Knp\Snappy\Pdf;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @var Pdf
     */
    private $pdf;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(Pdf $pdf, EntityManagerInterface  $entityManager)
    {
        $this->pdf = $pdf;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/view", options={"expose"=true} , name="view" )
     */
    public function index(SessionInterface  $session, EntityManagerInterface  $entityManager, Request  $request,  MailerInterface $mailer): Response
    {

//        /** @var User $user */
        $user = $this->getUser();
//
//
//        if ($this->isGranted('ROLE_CSE')){
//            $companyUser = $user->getCompany();
//            $filter = $entityManager->getFilters()->enable('softdeleteable');
//            $filter->disableForEntity(Cart::class);
//
//            $carts = $entityManager->getRepository(Cart::class)->createQueryBuilder('c')
//                ->leftJoin('c.users','cu')
//                ->leftJoin('cu.company','cuc')
//                ->where('cuc.id = :companyId')
//                ->setParameter('companyId', $companyUser->getId())
//                ->andWhere('c.sendByCSE = :sendByCSE')
//                ->andWhere('c.deletedAt is not null')
//                ->setParameter('sendByCSE', 0)
//                ->getQuery()
//                ->getResult();
//
//            $globalTotal = null;
//            foreach ($carts as $cart) {
//                $globalTotal += $cart->getTotal();
//            }
//            return $this->render('Front/Cart/index.html.twig', [
//                'carts' => $carts,
//                'globalTotal' => $globalTotal
//
//            ]);
//
//        }
//        else{
            $cartSession = $session->get('cart');
            if ($cartSession ==null){

                return $this->render('Front/Cart/index.html.twig', [
                    'cart' => null,

                ]);
            }

            $cart = $entityManager->getRepository(Cart::class)->find($cartSession->getId());

            $form = $this->createForm(CartType::class,  $cart, [
                'company'=>$this->getUser()->getCompany()->getId()
            ]);


            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {

                $address = $entityManager->getRepository(CompanyHasAddress::class)->find(intval($request->request->get('cart')['address']));
                $cart->setAddress($address->getZipCode() . ' '. $address->getCity(). ' - '. $address->getAddress());
                $cart->setName($user->getName());
                $cart->setFirstname($user->getFirstName());
                $cart->setActivity($user->getActivity());
                $cart->setEmail($user->getEmail());

                $cart->setDeletedAt(new \DateTime('now'));
                $entityManager->persist($cart);
                $entityManager->flush();
                $this->get('session')->remove('cart');
                $this->createPDFEmployee($cart);



                $pathPdf = $this->getParameter('kernel.project_dir').'/public/pdf/Cart/'.$cart->getId().'.pdf';

                $mails = [$cart->getEmail(), 'veronique.grammont@alliancemanufacturesdefrance.fr',  'adeline.norroy@alliancemanufacturesdefrance.fr'];

                foreach ($mails as $mail){
                    $email = (new TemplatedEmail())
                        ->from('veronique.grammont@alliancemanufacturesdefrance.fr')
                        ->to($mail)
                        ->subject('AMF - commande')
                        ->attachFromPath($pathPdf)
                        ->attachFromPath( $this->getParameter('kernel.project_dir').'/public/rib/rib.pdf')

                        ->htmlTemplate( 'Front/Order/mailEmployee.html.twig');

                    $mailer->send($email);
                }



                return $this->redirectToRoute('main');
            }


            return $this->render('Front/Cart/index.html.twig', [
                'cart' => $cart,
                'form' => $form->createView(),

            ]);
//        }


    }



    /**
     * @Route("/addItem/{productId}", options={"expose"=true},  name="add_item")
     */
    public function addItemOnCart(EntityManagerInterface $entityManager, CartService $cartService, Request $request, $productId)
    {


        /** @var Product $product */
        $product = $entityManager->getRepository(Product::class)->find($productId);



        $cartElement = new CartElement();
        $cart = $cartService->getCart();

        $combinations = $entityManager->getRepository(Combination::class)->createQueryBuilder('c')
            ->addSelect('ca')
            ->innerJoin('c.attributeItem', 'ca')
            //->innerJoin('ca.attribute', 'a')
            ->innerJoin('c.product', 'p')
            ->where('p.id = :productId')
            ->setParameter('productId', $productId)
            ->getQuery()
            ->getResult();

        $groupedCombinations = [];
        /** @var Combination $combination */
        foreach ($combinations as $combination) {
            $attributeName = $combination->getAttributeItem()->getAttribute()->getName();


            $groupedCombinations[$attributeName][] = [$attributeName=>$combination->getAttributeItem()->getValue(), 'priceCSE'=>$combination->getPriceCeTTC(), 'pricePublic'=>$combination->getPricePublicTTC(), 'combinationId'=>$combination->getId(), 'reference'=>$combination->getReference()];
        }


        $form = $this->createForm(CartElementType::class, $cartElement);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            if($request->request->get('select_combination')){
                /** @var Combination $combination */
                $combination = $entityManager->getRepository(Combination::class)->find($request->request->get('select_combination'));
            }


            if($product->getConfigurable() ==0){
                $cartElement->setProductPriceCETTEC($product->getPriceCeTTC());
                $cartElement->setProductPricePublicTTC($product->getPricePublicTTC());
                $cartElement->setProductReference($product->getReference());
            }else{
                $cartElement->setProductPriceCETTEC($combination->getPriceCeTTC());
                $cartElement->setProductPricePublicTTC($combination->getPricePublicTTC());
                $cartElement->setProductReference($combination->getReference());
            }
            $cartElement->setSend(0);
            $cartElement->setCart($cartService->getCart());
            $cartElement->setProductName($product->getName());
            $cartElement->setProduct($product);
            $cartElement->setProductImage($product->getImage());

            $cartElement->setQuantity($cartElement->getQuantity());

            if ($cartElement->getQuantity() > 0){
                $entityManager->persist($cartElement);
                $entityManager->flush();
            }

            return new JsonResponse(['r'=>'r']);

//            return $this->redirectToRoute('product_view', ['id'=>$productId]);
        }

        
        return $this->render('Front/Cart/addProduct.html.twig', [
            'form' => $form->createView(),
            'product'=>$product,
            'groupedCombinations'=>$groupedCombinations

        ]);
    }

    /**
     * @Route("/remove/{id}",options={"expose"=true}, name="remove")
     * @param Request $request
     * @return Response
     */
    public function remove($id, EntityManagerInterface $entityManager)
    {
        $cartElement = $entityManager->getRepository(CartElement::class)->find($id);

        $entityManager->remove($cartElement);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }

    /**
     * @Route("/quantity/{id}/{quantity}",options={"expose"=true}, name="quantity")
     * @param Request $request
     * @return Response
     */
    public function changeQuantity($id, $quantity, EntityManagerInterface $entityManager)
    {
        $cartElement = $entityManager->getRepository(CartElement::class)->find($id);

        $cartElement->setQuantity($quantity);
        $entityManager->persist($cartElement);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }


    /** @var Order $order */
    private function createPDFEmployee( $cart){

        $globalTotal = null;

        $globalTotal += $cart->getTotal();

        $pdf = $this->pdf;

        $pdf->generateFromHtml(
            $this->renderView(
                'Front/Order/pdfEmployee.html.twig', [
                'cart'=>$cart,
                'globalTotal'=>$globalTotal
            ]),
            $this->getParameter('kernel.project_dir').'/public/pdf/Cart/'.$cart->getId().'.pdf'
        );

        /**@var Cart $cart */
        $cart->setPdf($cart->getId().'.pdf');
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

    }


}
