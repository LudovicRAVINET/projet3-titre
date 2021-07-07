<?php

namespace App\Controller;

use App\Form\LoginType;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    /**
     * @Route("/login/google" , name="google_connect")
     */
    public function connect(ClientRegistry $clientRegistry): Response
    {
        $client = $clientRegistry->getClient('google');
        return $client->redirect(['profile'], ['email']);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        SubscriptionRepository $subscripRepository,
        MailerInterface $mailer
    ): Response {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($_POST['g-recaptcha-response'])) {
                $recaptcha = new ReCaptcha(strval($this->getParameter('recaptcha_key')));
                $response = $recaptcha->verify($_POST['g-recaptcha-response']);
                if ($response->isSuccess()) {
                    $hash = $encoder->encodePassword($user, $user->getPassword());
                    $user->setPassword($hash);

                    $freeSubscription = $subscripRepository->findOneBy(['name' => 'GRATUIT']);
                    if ($freeSubscription !== null) {
                        $freeSubscription->addUser($user);
                    }

                    $manager->persist($user);
                    $manager->flush();

                    //Automatic login after registration
                    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                    $this->container->get('security.token_storage')->setToken($token);
                    $this->container->get('session')->set('_security_main', serialize($token));
                     
                    // send email confirmation
                     $email = (new Email())
                      ->from(strval($this->getParameter('mailer_from')))
                      ->to(strval($user->getEmail()))
                      ->subject('Confirmation de votre inscription')
                      ->html($this->renderView('component/_email.html.twig'));

                     $mailer->send($email);

                    return $this->render('home/index.html.twig', ['newUser' => true]);
                } else {
                    $this-> addFlash('danger', 'Veuillez svp valider le RECAPTCHA');
                    return $this->redirectToRoute('home_index');
                }
            }
        }

        return $this->render('component/_register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/registerConfirm", name="app_register_confirm")
     */
    public function registerConfirm(SubscriptionRepository $subscripRepository): Response
    {
        $subscriptions = $subscripRepository->findAll();

        return $this->render('component/_registerConfirm.html.twig', ['subscriptions' => $subscriptions]);
    }
}
