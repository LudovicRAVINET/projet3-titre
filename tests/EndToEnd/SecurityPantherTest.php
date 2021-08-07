<?php

namespace App\Tests\EndToEnd;

use App\DataFixtures\UserFixtures;
use Facebook\WebDriver\WebDriverBy;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\Panther\PantherTestCase;

class SecurityPantherTest extends PantherTestCase
{
    use FixturesTrait;

    public function testRegisterIsValid(): void
    {
        $this->loadFixtures([UserFixtures::class], false, null, 'doctrine', 1);
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');
        $client->manage()->window()->maximize();

        $client->waitForVisibility('#home-login-link');
        $loginLink = $crawler->filter('#home-login-link')->link();
        $client->click($loginLink);

        $client->waitForVisibility('#register-link');
        $client->getWebDriver()->findElement(WebDriverBy::id('register-link'))->click();
        $client->waitForVisibility('h1#register-confirm-title');

        $this->assertSelectorTextContains('h1#register-confirm-title', 'S\'inscrire');

        $client->getWebDriver()->findElement(WebDriverBy::tagName("iframe"))->click(); // reCAPTCHA
        $form = $crawler->selectButton('S\'inscrire')->form([
            'user[firstname]' => 'Paul',
            'user[lastname]' => 'Ochon',
            'user[email]' => 'po' . rand(1, 999999) . '@gmail.com',
            'user[password]' => 'Password1',
            'user[birthDate]' => '30-07-1998'
        ]);
        $crawler = $client->submit($form);

        $client->waitForVisibility('h4#subscription-choice-title');
        $this->assertSelectorTextContains('h4#subscription-choice-title', 'votre compte a bien été créé');

        $entrepriseLink = $crawler->filter('a#Entreprise-btn')->link();
        $crawler = $client->click($entrepriseLink);
        $this->assertSelectorTextContains('h1#my-event-title', 'Mes évènements');

        // test add notice
        $noticeLink = $crawler->filter('a#notice-display-link')->link();
        $crawler = $client->click($noticeLink);
        $client->waitForVisibility('button#notice-submit-btn');

        $form = $crawler->selectButton('notice-submit-btn')->form();
        $form['notice[comment]'] = 'commentaire de test';
        $form['notice[note]'] = '4';
        $crawler = $client->submit($form);
        $this->assertSelectorTextContains('div.header-top-fix > div.alert', 'Merci pour votre avis');

        // test add event
        $createEventLink = $crawler->filter('a#eventCreateBtnNav')->link();
        $crawler = $client->click($createEventLink);
        $client->waitForVisibility('h2.whatEvent');
        $this->assertSelectorTextContains('h2.whatEvent', 'Quel type d\'évènement');

        $choiceEventLink = $crawler->filter('a#birthday-create-btn')->link();
        $crawler = $client->click($choiceEventLink);
    }
}
