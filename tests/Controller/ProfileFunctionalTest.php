<?php

namespace App\Tests\Controller;

use App\DataFixtures\EventFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileFunctionalTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function testProfilepageIsRestricted(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profile/2');

        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testAuthenticatedUserAccessProfilePage(): void
    {
        $client = static::createClient();

        $this->loadFixtures([UserFixtures::class, EventFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $this->login($client, $user);
            $client->request('GET', '/profile/' . $user->getId());
        }

        $this->assertResponseIsSuccessful();
    }
}
