<?php

namespace App\Controller\Front;

use App\Entity\Cart;
use App\Entity\User;
use App\Form\EmployeeByCseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/cse", name="cse_")
 */
class CseController extends AbstractController
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
    public function index(): Response
    {
        return $this->render('Front/Cse/index.html.twig', [
            'controller_name' => 'CseController',
        ]);
    }

    /**
     * @Route("/cseuserlist", name="cseuserlist")
     */
    public function cseUserList(EntityManagerInterface $entityManager): Response
    {
        $company = $this->getUser()->getCompany();
        $employees = $entityManager->getRepository(User::class)->findBy(['company'=>$company]);
        return $this->render('Front/Cse/userList.html.twig', [
            'controller_name' => 'CseController',
            'employees' =>$employees
        ]);
    }

    /**
     * @Route("/addemployee", name="addemployee")
     */
    public function addEmployee(EntityManagerInterface $entityManager, Request $request): Response
    {
        $company = $this->getUser()->getCompany();
        $employees = $entityManager->getRepository(User::class)->findBy(['company'=>$company]);
        $countEmployee = count($employees)+1;

        $user = new User();
        $form = $this->createForm(EmployeeByCseType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $passwordRandom = bin2hex( random_bytes(3));

            $username = $this->getUser()->getUsername().'-'.$countEmployee;

            $user->setUsername($username);
            $sendMail = null;
            if (!empty($form->getData()->getEmail())){
                $user->setEmail($form->getData()->getEmail());
                $sendMail = $form->getData()->getEmail();

            }else{
                $user->setEmail($this->getUser()->getEmail());

                $sendMail = $this->getUser()->getEmail();
            }
            $user->setCompany($company);



            // encode the plain password
            $user->setRoles(['ROLE_EMPLOYEE']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $passwordRandom
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $mails = [$sendMail,'yoann88110@gmail.com','veronique.grammont@alliancemanufacturesdefrance.fr',  'adeline.norroy@alliancemanufacturesdefrance.fr'];



            foreach ($mails as $mail){
                $email = (new TemplatedEmail())
                    ->from('veronique.grammont@alliancemanufacturesdefrance.fr')
                    ->to($mail)
                    ->subject('AMF - Création de compte')

                    ->htmlTemplate( 'Front/Cse/createUserEmployeeAccount.html.twig')
                    ->context(['username'=>$username, 'password'=>$passwordRandom]);

                $this->mailer->send($email);
            }

            return $this->redirectToRoute('cse_cseuserlist');
        }

        return $this->render('Front/Cse/add.html.twig', [
            'controller_name' => 'CseController',
            'employees' =>$employees,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/listorder", name="list_order")
     */
    public function listOrders(EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();


        if ($this->isGranted('ROLE_CSE')){
            $companyUser = $user->getCompany();
            $filter = $entityManager->getFilters()->enable('softdeleteable');
            $filter->disableForEntity(Cart::class);

            $carts = $entityManager->getRepository(Cart::class)->createQueryBuilder('c')
                ->leftJoin('c.users','cu')
                ->leftJoin('cu.company','cuc')
                ->where('cuc.id = :companyId')
                ->setParameter('companyId', $companyUser->getId())
                ->andWhere('c.sendByCSE = :sendByCSE')
                ->andWhere('c.deletedAt is not null')
                ->setParameter('sendByCSE', 0)
                ->getQuery()
                ->getResult();

            $globalTotal = null;
            foreach ($carts as $cart) {
                $globalTotal += $cart->getTotal();
            }
            return $this->render('Front/Cse/listOrder.html.twig', [
                'carts' => $carts,
                'globalTotal' => $globalTotal

            ]);

        }
        return $this->redirectToRoute('main');
    }

    /**
     * @Route("/edituser/{id}", name="edituser")
     */
    public function editUser(EntityManagerInterface $entityManager, Request $request, User $user)
    {
        $form = $this->createForm(EmployeeByCseType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Modification prise en compte'
            );
            return $this->redirectToRoute('cse_cseuserlist');



        }
        return $this->render('Front/Cse/editUser.html.twig', [
            'form' => $form->createView(),

        ]);
    }


    /**
     * @Route("/removeuser/{id}", options={"expose"=true}, name="removeuser")
     */
    public function removeUser(EntityManagerInterface $entityManager, Request $request, $id)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $entityManager->remove($user);;
        $entityManager->flush();

            $this->addFlash(
                'success',
                'Employée suprimée'
            );
        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);



    }
}
