<?php

namespace App\Tests\Controller;

use App\DataFixtures\EventFixtures;
use App\DataFixtures\TypeFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventFunctionalTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function testEventIsCreated(): void
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class, TypeFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $this->login($client, $user);
            $client->request('POST', '/event', [
                'event_type' => 'birthday',
                'event_name' => 'Anniv test fonctionnel',
                'event_date' => '2021-12-31',
                'event_time' => '18:30',
                'jackpot' => 'on',
            ]);
        }

        $this->assertResponseRedirects('/profile/2');
        $client->followRedirect();
        $this->assertSelectorTextContains('h1#my-event-title', 'Mes évènements');
        $this->assertSelectorTextContains('div.header-top-fix > div.alert', 'Votre événement a bien été créé.');
    }

    public function testEventIsNotCreatedBecauseNoUser(): void
    {
        $client = static::createClient();

        $client->request('POST', '/event');

        $this->assertResponseRedirects();
    }

    public function testEventIsNotCreatedBecauseNoEventTypeClicked(): void
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class, TypeFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $this->login($client, $user);
            $client->request('POST', '/event', [
                'event_name' => 'Anniv test fonctionnel',
                'event_date' => '2021-12-31',
                'event_time' => '18:30',
                'jackpot' => 'on',
            ]);
        }

        $this->assertResponseRedirects('/profile/2');
        $client->followRedirect();
        $this->assertSelectorTextContains('h1#my-event-title', 'Mes évènements');
        $this->assertSelectorTextContains('div.header-top-fix > div.alert', 'Veuillez selectionner un événement.');
    }

    public function testEventIsNotCreatedBecauseEventPastDate(): void
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class, TypeFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $this->login($client, $user);
            $client->request('POST', '/event', [
                'event_name' => 'Anniv test fonctionnel',
                'event_date' => '2020-12-31', // past date
            ]);
        }

        $this->assertResponseRedirects('/profile/2');
        $client->followRedirect();
        $this->assertSelectorTextContains('h1#my-event-title', 'Mes évènements');
        $this->assertSelectorTextContains(
            'div.header-top-fix > div.alert',
            'Veuillez selectionner une date postérieure à aujourd\'hui.'
        );
    }

    public function testDiaryDisplay(): void
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class, EventFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $this->login($client, $user);
            $client->request('GET', '/event/1/diary');
        }

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p.diary-part', 'Date et heure');
    }

    public function testDiaryNewMessageDisplay(): void
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class, EventFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $this->login($client, $user);

            // 'message' is form name
            /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $tokenManager */
            $tokenManager = $client->getContainer()->get('security.csrf.token_manager');
            $token = $tokenManager->getToken('message');
            $client->request('POST', '/event/1/diary', [
                'message' => [
                    'comment' => 'message de test fonctionnel',
                    '_token' => $token ?? ''
                ]
            ]);
        }

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('p.diary-part', 'Date et heure');
        $this->assertSelectorTextContains('p.diary-message-text', 'message de test fonctionnel');
    }
}
