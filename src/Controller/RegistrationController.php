<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Swift_Mailer;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\String\Slugger\SluggerInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request,Swift_Mailer $mailer, SluggerInterface $slugger, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();


            // Enregistrer les pp
            $nouvelInscrit = $form->getData();
            //dump($nouvelInscrit);
            $file = $form->get('avatar')->getData();
            //dump($file);
            /*if(!empty($file)) {*/
                /*$fileName = md5(uniqid()).'.'.$file->guessExtension();
                //$file = $form['attachment']->getData();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $user->setAvatar($fileName);*/
            /*} else {
                $user->setAvatar('default.png');
            }*/
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setAvatar($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            //Envoi de mail
            // Create the Transport

            $message =  (new \Swift_Message('Confirmation de ton inscription sur MegaOctet'))
                    // Expediteur
                    ->SetFrom('no-reply@gmail.com')

                    ->SetTo($nouvelInscrit->getMail())

                    //Vue du message
                    ->setBody
                    (
                        $this->renderView
                        (
                            'registration/confirmation.html.twig', compact('nouvelInscrit')
                        ),
                    'text/html'
                   )
            ;

            // J'envoie le message
            $mailer->send($message);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
