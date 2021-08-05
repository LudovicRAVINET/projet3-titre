<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeFunctionalTest extends WebTestCase
{
    public function testDisplayHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('span#what-eventoo', 'eventoo,');
    }

    public function testDisplayWeddingPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/wedding');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1#wedding-title', 'Tout pour organiser, inviter et partager votre mariage');
    }

    public function testDisplayBirthdayPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/birthday');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            'h1#birthday-title',
            'Tout pour organiser, inviter et partager votre anniversaire'
        );
    }

    public function testDisplayBirthPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/birth');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            'h1#birth-title',
            'Tout pour organiser, inviter et partager votre naissance'
        );
    }

    public function testDisplayMourningPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/mourning');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            'h1#mourning-title',
            'Il arrive parfois que l\'on ressente le besoin d\'exprimer'
        );
    }

    public function testDisplayOtherPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/other');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            'h1#other-title',
            'Tout pour organiser, inviter et partager votre évènement'
        );
    }
}
