<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SubscriptionFunctionalTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function testUserSubscriptionIsUpdated(): void
    {
        $client = static::createClient();

        $this->loadFixtures([UserFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $this->login($client, $user);
            $client->request('GET', '/subscription/choice/' . $user->getId());
        }

        $this->assertResponseRedirects();
    }
}
