<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityFunctionalTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function testDisplayLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form([
            'email' => 'user@eventoo.fr',
            'password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form([
            'email' => 'user@eventoo.fr',
            'password' => 'Password1'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/login_success');
        $client->followRedirect();
    }

    public function testNewUserIsRegistered(): void
    {
        $client = static::createClient();

        $captcha = '03AGdBq272HB4Q8rd_cusVfwa2Z1J-iAsy4CBbBW71MFFQsmVEPp_bOq98uXZiqYbDSVVLBcaph6gs
            2mavPFMroPlJpytOJ54YOetncKW0V9DwIOvDOSj7zBXlVynLYnutrJtLKAb3In2UAqiW28apYiQhnxHC3RKN6W
            d9i3pth0ZlfZlpaNw-4PLifTaClmCP3_uo-Z-dZF19Iq1pf_0n2JHDC-CCQ70mJvyfq2GzznzALcVhlNd61i5V
            VELEGvsHFLylCht_Xa1Upj68oZ73y6dFdBQWtMjKEU9uZzJ6AIl5e8s6cU9XBqEj9qIA7d4gon-8mmC5w7PDDU
            oAtgfi-5MA0kU4IkE35M4hUGq9v3dqMm17qdwNi2QLB7Xpp52Y6V6d9V7mK0JIZlUvgnfGNvPFfWQ0UOCYqRmJ
            bz1wqSz5ZW3iQ9gD_v1bcXL1JFf37mvoDmKLVGYp63gTrD_fdHE1KbUZ7EtrXA';
        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $tokenManager */
        $tokenManager = $client->getContainer()->get('security.csrf.token_manager');
        $token = $tokenManager->getToken('user');
        $crawler = $client->request('POST', '/register', [
            'user' => [
                'firstname' => 'Paul',
                'lastname' => 'Ochonouii',
                'email' => 'po' . rand(1, 999999) . '@gmail.com',
                'password' => 'Password1',
                'birthDate' => '1997-07-30',
                '_token' => $token
            ],
            'g-recaptcha-response' => $captcha
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testNewUserIsNotRegisteredBecauseOfRecaptchaNotClicked(): void
    {
        $client = static::createClient();

        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $tokenManager */
        $tokenManager = $client->getContainer()->get('security.csrf.token_manager');
        $token = $tokenManager->getToken('user');
        $crawler = $client->request('POST', '/register', [
            'user' => [
                'firstname' => 'Paul',
                'lastname' => 'Ochonouii',
                'email' => 'po' . rand(1, 999999) . '@gmail.com',
                'password' => 'Password1',
                'birthDate' => '1997-07-30',
                '_token' => $token
            ],
            'g-recaptcha-response' => ''
        ]);

        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertSelectorTextContains('div.header-top-fix > div.alert', 'Veuillez svp valider le RECAPTCHA');
    }

    public function testForgottenPasswordIsInvalidBecauseOfUnknownEmail(): void
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/forgottenPassword', [
            'reset_password' => [
                'email' => 'inexistant@gmail.com',
            ],
        ]);

        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertSelectorTextContains('div.header-top-fix > div.alert', 'Cette adresse e-mail est inconnue');
    }

    public function testForgottenPasswordIsValid(): void
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);
        if ($user != null) {
            $validUserMail = $user->getEmail();
            $crawler = $client->request('POST', '/forgottenPassword', [
                'reset_password' => [
                    'email' => $validUserMail,
                ],
            ]);
        }

        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertSelectorTextContains(
            'div.header-top-fix > div.alert',
            'E-mail de réinitialisation du mot de passe envoyé !'
        );
    }

    public function testResetPasswordIsUpdated(): void
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $user->setResetToken('falseToken');

            /** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
            $doctrine = static::getContainer()->get('doctrine');
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->login($client, $user);
            $crawler = $client->request('POST', '/resetPassword/falseToken', [
                'password' => 'PasswordTestReset',
            ]);
        }

        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertSelectorTextContains('div.header-top-fix > div.alert', 'Mot de passe mis à jour');
    }

    public function testResetPasswordIsNotUpdatedBecauseOfWrongToken(): void
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $user->setResetToken('wrongToken');

            /** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
            $doctrine = static::getContainer()->get('doctrine');
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->login($client, $user);
            $crawler = $client->request('GET', '/resetPassword/falseToken');
        }

        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertSelectorTextContains('div.header-top-fix > div.alert', 'Token Inconnu');
    }

    public function testResetPasswordFormIsAccessible(): void
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);
        if ($user != null) {
            $user->setResetToken('FalseToken');

            /** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
            $doctrine = static::getContainer()->get('doctrine');
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->login($client, $user);
            $crawler = $client->request('GET', '/resetPassword/falseToken');
        }

        $this->assertResponseIsSuccessful();
    }
}
