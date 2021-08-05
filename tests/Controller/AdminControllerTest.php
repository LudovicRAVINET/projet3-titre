<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;

class AdminControllerTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function testRoleUserNotAccessAdminPage(): void
    {
        $client = static::createClient();

        $this->loadFixtures([UserFixtures::class]);

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(2);

        if ($user != null) {
            $this->login($client, $user);
        }

        $client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(403);
    }

    /*public function testRoleAdminAccessAdminPage(): void
    {
        $client = static::createClient();

        $this->loadFixtures([UserFixtures::class]);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(1);
        $this->login($client, $user);

        $client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();
    }*/
}
