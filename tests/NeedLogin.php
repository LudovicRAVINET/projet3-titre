<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait NeedLogin
{
    public function login(KernelBrowser $client, User $user): void
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $client->getContainer()->get('session');
        if ($session != null) {
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $session->set('_security_main', serialize($token));
            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $client->getCookieJar()->set($cookie);
        }
    }
}
