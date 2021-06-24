<?php

namespace App\Controller;

use App\Form\LoginType;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use App\Entity\User;
use App\Form\ResetPasswordType;
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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
        SubscriptionRepository $subscripRepository
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

    /**
     * @Route("/forgottenPassword", name="app_forgotten_password", methods={"POST"})
     */
    public function reset(
        Request $request,
        UserRepository $users,
        MailerInterface $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response {

        // This form is not linked to an entity, be carefull /\
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $user = $users->findOneByEmail($data['email']);

            if ($user === null) {
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
                return $this->redirectToRoute('home_index');
            }

            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('home_index');
            }

            $url = $this->generateUrl(
                'app_reset_password',
                array('token' => $token),
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $message = (new Email())
                        ->from('admin@eventoo.fr')
                        ->to($user->getEmail())
                        ->subject('Réinitialisation de votre mot de passe')
                        ->html(
                            "Bonjour,<br><br>Une demande de réinitialisation de mot
                             de passe a été effectuée. Veuillez cliquer sur le lien suivant : " . $url,
                            'text/html'
                        );

            $mailer->send($message);

            $this->addFlash('success', 'E-mail de réinitialisation du mot de passe envoyé !');

            return $this->redirectToRoute('home_index');
        }

        return $this->render('component/_forgottenPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/resetPassword/{token}", name="app_reset_password")
     */
    public function resetPassword(
        Request $request,
        string $token,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        if ($user === null) {
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('home_index');
        }

        if ($request->isMethod('POST')) {
            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe mis à jour');

            return $this->redirectToRoute('home_index');
        } else {
            return $this->render('component/_resetPassword.html.twig', ['token' => $token]);
        }
    }
}
