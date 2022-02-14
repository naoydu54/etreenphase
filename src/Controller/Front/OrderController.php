<?php

namespace App\Controller\Front;

use App\Entity\Cart;
use App\Entity\CartElement;
use App\Entity\Company;
use App\Entity\Order;
use App\Entity\OrderElement;
use App\Entity\OrderHasStatus;
use App\Entity\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Snappy\Pdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order", name="order_")
 */
class OrderController extends AbstractController
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
     * @Route("/{cartId}/{companyId}", name="add")
     */
    public function add( $cartId, $companyId,  EntityManagerInterface  $entityManager, MailerInterface $mailer): Response
    {
        $filter = $entityManager->getFilters()->enable('softdeleteable');
        $filter->disableForEntity(Cart::class);

//        $carts = $entityManager->getRepository(Cart::class)->createQueryBuilder('c')
//            ->leftJoin('c.users','cu')
//            ->leftJoin('cu.company','cuc')
//            ->where('cuc.id = :companyId')
//            ->setParameter('companyId', $companyId)
//            ->andWhere('c.sendByCSE = :sendByCSE')
//            ->setParameter('sendByCSE', 0)
//            ->getQuery()
//            ->getResult();

        /** @var Cart $cart */

        $cart = $entityManager->getRepository(Cart::class)->createQueryBuilder('c')
            ->where('c.id = :cartId')
            ->setParameter('cartId', $cartId)
            ->andWhere('c.sendByCSE = :sendByCSE')
            ->setParameter('sendByCSE', 0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $order = new Order();

        $order->setUser($cart->getUsers()->first());

        $company = $entityManager->getRepository(Company::class)->find($companyId);
        $order->setCompany($company);

        $statusOrder = $entityManager->getRepository(OrderStatus::class)->findOneBy(['name'=>'Nouveau']);

        $order->setOrderStatus($statusOrder);

//            $order->addCart($cart);

            $cart->setSendByCSE(1);
            $order->addCart($cart);
            $entityManager->persist($cart);


            /** @var CartElement $cartElement */
            foreach ($cart->getCartElements() as $cartElement) {
                $orderElement = new OrderElement();
                $orderElement->setProductName($cartElement->getProductName());
                $orderElement->setProductPriceCETTEC($cartElement->getProductPriceCETTEC());
                $orderElement->setProductPricePublicTTC($cartElement->getProductPricePublicTTC());
                $orderElement->setProductReference($cartElement->getProductReference());
                $orderElement->setQuantity($cartElement->getQuantity());
                $orderElement->setProductReference($cartElement->getProductReference());
                $orderElement->setOrder($order);

                $entityManager->persist($orderElement);

            }
            $entityManager->persist($order);



        $entityManager->flush();


        $this->createPDF($order, $cart);

        $pathPdf = $this->getParameter('kernel.project_dir').'/public/pdf/Order/'.$order->getId().'.pdf';

        $mails = [$company->getEmail(), 'veronique.grammont@alliancemanufacturesdefrance.fr',  'adeline.norroy@alliancemanufacturesdefrance.fr'];


        foreach ($mails as $mail){
            $email = (new TemplatedEmail())
                ->from('veronique.grammont@alliancemanufacturesdefrance.fr')
                ->to($mail)

                ->subject('AMF - Commande')
                ->attachFromPath($pathPdf)
                ->attachFromPath( $this->getParameter('kernel.project_dir').'/public/rib/rib.pdf')

                ->htmlTemplate( 'Front/Order/mailCSE.html.twig');

            $mailer->send($email);
        }


        return  $this->redirectToRoute('main');
    }

    /** @var Order $order */
    private function createPDF( $order, $cart){

        $globalTotal = null;
            $globalTotal = $cart->getTotal();

        $pdf = $this->pdf;

        $pdf->generateFromHtml(
            $this->renderView(
                'Front/Order/pdf.html.twig', [
                    'order'=>$order,
                    'cart'=>$cart,
                    'globalTotal'=>$globalTotal
            ]),
            $this->getParameter('kernel.project_dir').'/public/pdf/Order/'.$order->getId().'.pdf'
        );

        $order->setPdfOrder($order->getId().'.pdf');
        $this->entityManager->persist($order);
        $this->entityManager->flush();

    }
}
