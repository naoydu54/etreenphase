<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class CartService
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var Security
     */
    private $security;


    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager, SessionInterface $session,   Security $security)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->security = $security;
    }

    public function getCart()
    {

        $userId = $this->security->getUser()->getId();
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $cartSession = $this->session->get('cart');
        $cart = null;


        if ($cartSession == null) {

            $cart = new \App\Entity\Cart();
            $cart->addUser($user);
            $cart->setSendByCSE(0);

            $user->addCart($cart);
            $this->entityManager->persist($cart);
            $this->entityManager->persist($user);

            $this->entityManager->flush();


            $this->session->set('cart', $cart);
            return $cart;
        }else{


            $cart = $this->entityManager->getRepository(Cart::class)->find($cartSession->getId());

            if($cart ==null){
                $cart = new \App\Entity\Cart();

                $cart->setUser($user);
                $cart->addUser($user);
                $user->addCart($cart);
                $cart->setSendByCSE(0);

                $this->session->set('cart', $cart);
                $this->entityManager->persist($cart);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $cart;

            }
            return $cart;

        }



//        return $cart;

    }

}