<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use App\Entity\CartElement;
use App\Entity\Order;
use App\Entity\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order", name="admin_order_")
 */
class OrderController extends AbstractController
{
    private  $entityManager;
    private  $mailer;
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }


    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('Admin/Order/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * @Route("/ajaxdata", options={"expose"=true},  name="ajax_data")
     */
    public function ajaxData(EntityManagerInterface $entityManager): Response
    {
        $orders = $entityManager->getRepository(Order::class)->findAll();
        $filter = $entityManager->getFilters()->enable('softdeleteable');
        $filter->disableForEntity(Cart::class);
        $preOrders = $entityManager->getRepository(Cart::class)->createQueryBuilder('c')
            ->innerJoin('c.users', 'cu')
            ->innerJoin('cu.company','c2')
            ->innerJoin('c.cartElements','ce')
            ->where('c.sendByCSE = :sendByCSE')
            ->setParameter('sendByCSE', 0)
            ->andWhere('ce > :countElement')
            ->setParameter('countElement', 1)
            ->andWhere('c.deletedAt is not NULL')
            ->getQuery()
            ->getResult();

        $datas = [];
//
        /** @var Cart $preOrder */
        foreach ($preOrders as $preOrder) {
            $user = $preOrder->getUsers()->first();
            $company = $user->getCompany()->getName();
            $date = $preOrder->getCreateddAt();
            $date = $date->format('d/m/Y');

            $datas['data'][] = [
                'id' => $preOrder->getId(),
                'status'=>'PRE  COMMANDE',
                'company'=>$company,
                'date'=>$date

            ];

        }

        foreach ($orders as $order) {
            $date = $order->getCreateddAt();
            $date = $date->format('d/m/y');
            $datas['data'][] = [
                'id' => $order->getId(),
                'status'=>$order->getOrderStatus()->getName(),
                'company'=>$order->getCompany()->getName(),
                'date'=>$date

            ];

        }
        return new JsonResponse($datas);

    }


    /**
     * @Route("/view/{id}/{orderType}", options={"expose"=true},  name="view")
     */
    public function view($id, $orderType, EntityManagerInterface $entityManager)
    {
        $filter = $entityManager->getFilters()->enable('softdeleteable');

        $filter->disableForEntity(Cart::class);

        if ($orderType === 'PRE  COMMANDE'){
            $cart = $entityManager->getRepository(Cart::class)->find($id);
            $user = $cart->getUsers()->first();
            $company = $user->getCompany()->getName();
            return $this->render('Admin/Order/viewCart.html.twig', [
                'controller_name' => 'PageController',
                'cart'=>$cart,
                'company' => $company,
                'user'=>$user,
            ]);

        }else{
            $order = $entityManager->getRepository(Order::class)->find($id);
            $globalTotal = null;
            foreach ($order->getCarts() as $cart) {
                $globalTotal += $cart->getTotal();
            }
            return $this->render('Admin/Order/view.html.twig', [
                'controller_name' => 'PageController',
                'order'=>$order,
                'globalTotal'=>$globalTotal
            ]);

        }



        return new Response('ok');
    }

    /**
     * @Route("/productsend/{data}", options={"expose"=true},  name="product_send")
     */
    public function updateProductSend($data)
    {

        $notSends = [];
        $sends = [];


        $data = json_decode($data);

        foreach ($data as $datum) {
            if ($datum->{'notSend'}){
                $notSends[]= $datum->{'notSend'};
            }

            if ($datum->{'send'}){
                $sends[]= $datum->{'send'};
            }

        }
//




        foreach ($notSends as $notSend) {
            /** @var CartElement $cartElement */
            $cartElement = $this->entityManager->getRepository(CartElement::class)->find($notSend);
            $cartElement->setSend(1);
            $this->entityManager->persist($cartElement);
            $this->entityManager->flush();

        }

        foreach ($sends as $send) {
            /** @var CartElement $cartElement */
            $cartElement = $this->entityManager->getRepository(CartElement::class)->find($send);
            $cartElement->setSend(0);
            $this->entityManager->persist($cartElement);
            $this->entityManager->flush();

        }


        $r = ['success'=>'success'];

        return new JsonResponse($r);
    }

    /**
     * @Route("/updateorderstatus/{orderId}/{statusId}", options={"expose"=true},  name="update_order_status")
     */
    public function updateOrderStatus($orderId, $statusId)
    {
        $orderId = intval($orderId);
        $statusId = intval($statusId);

        $status = $this->entityManager->getRepository(OrderStatus::class)->find($statusId);

        /** @var Order $order */
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);


            if($order->getOrderStatus()->getId() != $statusId){
                $order->setOrderStatus($status);
                try {
                    $this->sendMail($order, $statusId);

                }catch (\Exception $e){

                }
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();


        $r = ['success'=>'success'];

        return new JsonResponse($r);

    }

    private function sendMail($order, $statusId ){
        /**@var \App\Entity\Order $order **/
        /**@var \App\Entity\User $user **/
        $subject = null;
        $mailsEmployee =  [
            $order->getUser()->getEmail(),
            'veronique.grammont@alliancemanufacturesdefrance.fr',
            'adeline.norroy@alliancemanufacturesdefrance.fr'
        ];
        $mailsCse = [
            $order->getUser()->getCompany()->getEmail(),
            'veronique.grammont@alliancemanufacturesdefrance.fr',
            'adeline.norroy@alliancemanufacturesdefrance.fr'
        ];

        switch ($statusId){
            case 1: {
                break;
            }

            case 2:{
                $subject = 'Payé';

                $htmlTemplateCse=('Admin/Order/MailStatus/Cse/paye.html.twig');
                $htmlTemplateEmployee=('Admin/Order/MailStatus/Employee/paye.html.twig');
                break;
            }

            case 3:{
                $subject = 'En cours de livraison';
                $htmlTemplateCse=('Admin/Order/MailStatus/Cse/encourslivraison.html.twig');
                $htmlTemplateEmployee=('Admin/Order/MailStatus/Employee/encourslivraison.html.twig');
                break;
            }

            case 4:{
                $subject = 'Livré';
                $htmlTemplateCse=('Admin/Order/MailStatus/Cse/livre.html.twig');
                $htmlTemplateEmployee=('Admin/Order/MailStatus/Employee/livre.html.twig');
                break;
            }
        }




        foreach ($mailsEmployee as $mailEmployee){
            $email = (new TemplatedEmail())
                ->from('veronique.grammont@alliancemanufacturesdefrance.fr')
                ->to($mailEmployee)
                ->subject('AMF - ' . $subject)

                ->htmlTemplate( $htmlTemplateEmployee);

            $this->mailer->send($email);
        }

        foreach ($mailsCse as $mailCse){
            $email = (new TemplatedEmail())
                ->from('veronique.grammont@alliancemanufacturesdefrance.fr')
                ->to($mailCse)
                ->subject('AMF - ' . $subject)

                ->htmlTemplate( $htmlTemplateCse);

            $this->mailer->send($email);
            sleep(1);

        }
    }
}
