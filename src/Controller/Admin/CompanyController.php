<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\CompanyHasMenu;
use App\Entity\Menu;
use App\Entity\User;
use App\Form\AuthorizedMenuType;
use App\Form\CompanyType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/company", name="admin_company_")
 */

class CompanyController extends AbstractController
{

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer, SessionInterface $session)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
        $this->session = $session;

    }

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('Admin/Company/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * @Route("/ajaxdata", options={"expose"=true},  name="ajax_data")
     */
    public function ajaxData(EntityManagerInterface $entityManager): Response
    {
        $companies = $entityManager->getRepository(Company::class)->findAll();
        $datas = [];

        foreach ($companies as $company) {
            $datas['data'][] = [
                'id' => $company->getId(),
                'name' => $company->getName()
            ];

        }
        return new JsonResponse($datas);

    }

    /**
     * @Route("/add", name="add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('admin_company_edit', ['id'=>$company->getId()]);
        }


        return $this->render('Admin/Company/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/edit/{id}",options={"expose"=true}, name="edit")
     * @param Request $request
     * @return Response
     */
    public function edit($id, EntityManagerInterface $entityManager, Request  $request)
    {
        $company = $entityManager->getRepository(Company::class)->find($id);

        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('admin_company_edit', ['id'=>$company->getId()]);
        }


        return $this->render('Admin/Company/add.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),

        ]);

    }

    /**
     * @Route("/remove/{id}",options={"expose"=true}, name="remove")
     * @param Request $request
     * @return Response
     */
    public function remove($id, EntityManagerInterface $entityManager)
    {
        $company = $entityManager->getRepository(Company::class)->find($id);

        $entityManager->remove($company);
        $entityManager->flush();

        $r = [
            'success' => ['success'],
        ];

        return new JsonResponse($r);

    }

    /**
     * @Route("/createUser/{id}",options={"expose"=true}, name="create_user")
     * @param Request $request
     * @return Response
     */
    public function createUser($id,Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $company = $entityManager->getRepository(Company::class)->find($id);
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'action'=>$this->generateUrl('admin_company_create_user', ['id'=>$company->getId()])
        ]);


        $passwordCSE = bin2hex( random_bytes(3));
        $user->setPassword($passwordEncoder->encodePassword(
            $user,
            $passwordCSE
        ));
        $user->setCompany($company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordRandom = bin2hex( random_bytes(3));

            $userEmployee = new User();
            $userEmployee->setCompany($company);
            $userEmployee->setUsername($user->getUsername().  1);
            $userEmployee->setRoles(['ROLE_EMPLOYEE']);
            $userEmployee->setEmail($user->getEmail());
            $userEmployee->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $passwordRandom
                )
            );


            // encode the plain password
            $user->setRoles(['ROLE_CSE']);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($userEmployee);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $email = (new TemplatedEmail())
                ->from('hello@example.com')
                ->to('you@example.com')

                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
//                ->html('<p> user: '. $user->getUsername() . '<br> username:  ' . $userEmployee->getUsername() . '  password: '.$passwordRandom  );
                ->htmlTemplate( 'Admin/Registration/createAccount.html.twig')
                ->context([
                    'userCSE' => $user->getUsername(),
                    'passwordCSE' => $passwordCSE,
                    'userEmployee' => $userEmployee->getUsername(),
                    'employeePassword' => $passwordRandom,
                ]);


            $mailer->send($email);

            return $this->redirectToRoute('admin_main');
        }

        return $this->render('Admin/Registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/authorized_menu/{id}",options={"expose"=true}, name="authorized_menu")
     * @param Request $request
     * @return Response
     */
    public function authorizeddMenu(Request $request,EntityManagerInterface $entityManager, $id)
    {

        return $this->render('Admin/Company/authorizedMenu.html.twig', [
            'controller_name' => 'PageController',
            'company'=>$id

        ]);

    }

    /**
     * @Route("/ajaxMenu/{companyId}",options={"expose"=true}, name="ajaxmenu")
     * @param Request $request
     * @return Response
     */
    public function ajaxMenu(EntityManagerInterface $entityManager, $companyId)
    {
        $jsonMenus = [];
        $menus = $entityManager->getRepository(Menu::class)->createQueryBuilder('m')
            ->groupBy('m.root')
            ->getQuery()
            ->getResult();

        /** @var Menu  $menu */
        foreach ($menus as $menu) {
            $selected = false;
            foreach ($menu->getCompanies() as $value) {
                if ($value->getId() == $companyId) {
                    $selected = true;
                }
            }

            $jsonMenus[] = ['id' => $menu->getId(), 'parent' => '#', 'text' => $menu->getName(), 'state'=>['selected'=>$selected]];


            if (count($menu->getChildren())> 0 ){
                foreach ($menu->getChildren() as $children) {

                    $selected = false;
                    foreach ($children->getCompanies() as $value) {
                        if ($value->getId() == $companyId) {
                            $selected = true;
                        }
                    }

                    $jsonMenus[] = ['id' => $children->getId(), 'parent' => $children->getParent()->getId(), 'text' => $children->getName(), 'state'=>['selected'=>$selected]];

                }
            }
        }

        return new JsonResponse($jsonMenus);
    }


    /**
     * @Route("/ajaxUpadteMenu/{companyId}/{datas}",options={"expose"=true}, name="ajax_update_menu")
     * @param Request $request
     * @return Response
     */
    public function updateMenu(EntityManagerInterface $entityManager, $companyId, $datas=null)
    {

        $company = $entityManager->getRepository(Company::class)->find($companyId);

        foreach ($company->getMenus() as $menu){
            $company->removeMenu($menu);
            $menu->removeCompany($company);
            $entityManager->persist($company);
            $entityManager->persist($company);
            $entityManager->flush();

        }


        $dataIdMenus = explode(',', $datas);

        if(!empty( $dataIdMenus)){
            foreach ( $dataIdMenus as $item) {
                $menu = $entityManager->getRepository(Menu::class)->find($item);
                $menu->addCompany($company);
                $entityManager->persist($company);
                $entityManager->flush();
            }
        }




        $r = [
            'success'=>"success"
        ];
        return new JsonResponse($r);
    }

    /**
     * @Route("/exel/{id}",options={"expose"=true}, name="excel")
     * @param Request $request
     * @return Response
     */
    public function excel($id, EntityManagerInterface $entityManager, Request  $request, SluggerInterface $slugger)
    {
        $company = $entityManager->getRepository(Company::class)->find($id);

        $role = 'ROLE_CSE';
        $usernameRoleCse = $entityManager->getRepository(User::class)->createQueryBuilder('u')
            ->innerJoin('u.company','uc')
            ->where('uc.id = :id')
            ->setParameter('id', $id)

            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();


        $form = $this->createFormBuilder()
            ->add('file', FileType::class,[

                'label'=>false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => ' btn btn-primary',
                    'placeholder' => 'Soumette'
                ],
                'label'=>"Soumettre"
            ])

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $file = $form->get('file')->getData();

            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL

            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            $path = $this->getParameter('kernel.project_dir').'/public/excel';

            $file->move(
                $path,
                $newFilename
            );


            $patternPrenom = "/[P-p][R-r][E-e][N-n][O-o][M-m]/";
            $patternNom = "/[N-n][O-o][M-m]/";
            $patternEmail = "/[E-e][M-m][A-a][I-i][L-l]/";
            $patternService = "/[S-s][E-e][R-r]['V-v'][I-i][C-c][E-e]/";

            $spreadsheet = IOFactory::load($path.'/'.$newFilename);

            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($worksheet->getRowIterator() as $k=>$row) {
                if (preg_match($patternNom, $worksheet->getCell('A'.$k)->getValue()) !==1 ||preg_match($patternPrenom, $worksheet->getCell('B'.$k)->getValue()) !==1 ||preg_match($patternEmail, $worksheet->getCell('C'.$k)->getValue()) !==1 ||preg_match($patternService, $worksheet->getCell('D'.$k)->getValue()) !==1)
                    $rows[]=['nom'=>$worksheet->getCell('A'.$k)->getValue(), 'prenom'=>$worksheet->getCell('B'.$k)->getValue(), 'email'=>$worksheet->getCell('C'.$k)->getValue(),'service'=>$worksheet->getCell('D'.$k)->getValue()];

            }

            $countAddUser = 0;
            foreach ($rows as $row) {
                $userAlreadyExisting = $entityManager->getRepository(User::class)->findOneBy(['email'=>$row['email']]);
                if (!$userAlreadyExisting){
                    $countAddUser ++;

                    $employees = $entityManager->getRepository(User::class)->findBy(['company'=>$company]);
                    $countEmployee = count($employees)+1;

                    $user = new User();
                    $user->setName($row['nom']);
                    $user->setFirstname($row['prenom']);
                    $user->setEmail($row['email']);
                    $user->setActivity($row['service']);
                    $user->setCompany($company);
                    $passwordRandom = bin2hex( random_bytes(3));

                    $username = $usernameRoleCse->getUsername().'-'.$countEmployee;
                    $user->setUsername($username);
                    $user->setRoles(['ROLE_EMPLOYEE']);
                    $user->setPassword(
                        $this->passwordEncoder->encodePassword(
                            $user,
                            $passwordRandom
                        )
                    );

                    $entityManager->persist($user);
                    $entityManager->flush();
                    $mails = [$row['email']];

                    foreach ($mails as $mail){
                        $email = (new TemplatedEmail())
                            ->from('veronique.grammont@alliancemanufacturesdefrance.fr')
                            ->to($mail)
                            ->subject('AMF - Création de compte')

                            ->htmlTemplate( 'Front/Cse/createUserEmployeeAccount.html.twig')
                            ->context(['username'=>$username, 'password'=>$passwordRandom]);

                        $this->mailer->send($email);
                    }

                }

            }
            $this->addFlash(
                'success',
                'Importation effectué'
            );

        }


        return $this->render('Admin/Company/excel.html.twig', [
            'form'=>$form->createView(),
            'company'=>$company


        ]);

    }

    /**
     * @Route("/userList/{companyId}",options={"expose"=true}, name="user_list")
     */
    public function UserList($companyId): Response
    {
        $this->session->set('companyId', $companyId);

        return $this->render('Admin/Company/userList.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * @Route("/UserListAjax/",options={"expose"=true}, name="user_list_ajax")
     */
    public function UserListAjax(EntityManagerInterface $entityManager): Response
    {

        $companyId = $this->session->get('companyId');


        $users = $entityManager->getRepository(User::class)->findBy(['company'=>$companyId]);
        $datas = [];

        foreach ($users as $user) {

            $datas['data'][] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'firstname' => $user->getFirstName(),
                'activity' => $user->getActivity(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),

            ];

        }
        return new JsonResponse($datas);
    }
}
