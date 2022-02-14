<?php

namespace App\Controller\Front;

use App\Entity\Company;
use App\Entity\Menu;
use App\Entity\User;
use App\Form\CompanyType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{

    private $passwordEncoder;
    private $mailer;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/addcompany", name="addcompany")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company, [
            'action'=>$this->generateUrl('user_addcompany'),
        ]);


        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            if (count($form->getData()->getCompanyHasAdresses()) ==0){

                $this->addFlash(
                    'danger',
                    'vous devez remplir une adresse'
                );
                return $this->redirectToRoute('user_addcompany');

            }

            $menus = $entityManager->getRepository(Menu::class)->findAll();
            foreach ($menus as $menu) {
                $company->addMenu($menu);
                $entityManager->persist($company);

            }

            $entityManager->persist($company);
            $entityManager->flush();

            $this->register($form, $company);
            $this->addFlash(
                'success',
                'Votre compte a été crée un mail vous sera envoyé'
            );


            return $this->redirectToRoute('main');
        }


        return $this->render('Front/User/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function register($form, $company): Response
    {
        $user = new User();


            $passwordRandom = bin2hex( random_bytes(3));
            $companyName = $this->str_without_accents($form->getData()->getName());
            $siteConcerned = $this->str_without_accents($form->getData()->getSiretConcerned());

            $username= 'cse-'.$companyName.$siteConcerned;
            $user->setUsername($username);
            $user->setEmail($form->getData()->getEmail());
            $user->setName($form->getData()->getName());
            $user->setFirstName($form->getData()->getFirstname());
            $user->setActivity('CSE');
            $user->setCompany($company);



            // encode the plain password
            $user->setRoles(['ROLE_CSE']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $passwordRandom
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            $mails = [$form->getData()->getEmail(), 'test-7bckbb65g@srv1.mail-tester.com', 'yoann88110@gmail.com', 'veronique.grammont@alliancemanufacturesdefrance.fr',  'adeline.norroy@alliancemanufacturesdefrance.fr'];

        foreach ($mails as $mail){
            $email = (new TemplatedEmail())
                ->from('veronique.grammont@alliancemanufacturesdefrance.fr')
                ->to($mail)
                ->subject('AMF - Création de compte')

                ->htmlTemplate( 'Front/User/createUserCseAccount.html.twig')
                ->context(['username'=>$username, 'password'=>$passwordRandom]);

            $this->mailer->send($email);
        }


            return $this->redirectToRoute('main');


    }
    function str_without_accents($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);
        $str = strtolower($str);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
        $str = preg_replace('/(\'|&#0*39;)/', '', $str);
        $str = preg_replace('/&#0*39;|\'/', '', $str);
        $str = preg_replace('/[^a-zA-Z0-9\']/', '', $str);


        return $str;   // or add this : mb_strtoupper($str); for uppercase :)
    }
}
