<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("/contact", name="contact_")
 */
class ContactController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {


        $form = $this->createFormBuilder()
            ->add('company', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la recette ou tutoriel'
                ],
                'label' => 'Entreprise'
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'email'
                ],
                'label' => 'email'
            ])
            ->add('object', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'objet'
                ],
                'label' => 'objet'
            ])

            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'message'
                ],
                'label' => 'message'
            ])
            ->add('send', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Envoyer message'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys

            $code = $request->request->get('g-recaptcha-response');
            $reponseRecaptcha = $this->recaptcha($code);



            if ($reponseRecaptcha == true) {
                $email = (new Email())
                    ->from($form->getData()['email'])
                    ->to('contact@amf.fr')
                    ->replyTo($form->getData()['email'])
                    ->subject($form->getData()['company'].' '. $form->getData()['object'])
                    ->html('<p>'.$form->getData()['message'].'</p>');

                $mailer->send($email);
                $this->addFlash(
                    'notice',
                    'Mail envoyée avec succès'
                );
                return $this->redirectToRoute('contact_main');

            }

        }


        return $this->render('Front/Contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView()
        ]);
    }

    private function recaptcha($code)
    {
        if (empty($code)) {
            return false; // Si aucun code n'est entré, on ne cherche pas plus loin
        }
        $params = [
            'secret' => '6Lc0ZUYbAAAAAEP-_rx2Gn8JJyPsRgA_ctDqtMKS',
            'response' => $code
        ];

        $url = "https://www.google.com/recaptcha/api/siteverify?" . http_build_query($params);
        if (function_exists('curl_version')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Evite les problèmes, si le ser
            $response = curl_exec($curl);
        } else {
            // Si curl n'est pas dispo, un bon vieux file_get_contents
            $response = file_get_contents($url);
        }

        if (empty($response) || is_null($response)) {
            return false;
        }

        $json = json_decode($response);
        return $json->success;
    }
}
