<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Message;
use App\Entity\Notice;
use App\Entity\Subscription;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserEntityTest extends KernelTestCase
{
    private const EMAIL_CONSTRAINT_MESSAGE = 'L\'email n\'est pas valide.';
    private const NOT_BLANK_CONSTRAINT_MESSAGE = 'Veuillez saisir une valeur.';
    private const INVALID_EMAIL_VALUE = 'john@gmail';
    private const VALID_EMAIL_VALUE = 'john@gmail.com';
    private const VALID_PASSWORD_VALUE = 'Password1';
    private const VALID_FIRSTNAME_VALUE = 'John';
    private const VALID_LASTNAME_VALUE = 'Doe';
    private const BIRTHDATE_CONSTRAINT_MESSAGE = 'L\'utilisateur doit avoir plus de 18 ans.';
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        /** @var \Symfony\Component\Validator\Validator\ValidatorInterface $validatorService */
        $validatorService = $kernel->getContainer()->get('validator');

        if ($validatorService != null) {
            $this->validator = $validatorService;
        }
    }

    public function testIsTrue(): void
    {
        $user = new User();
        $subscription = new Subscription();
        $birthdate = new DateTime();
        $event = new Event();
        $message = new Message();
        $notice = new Notice();

        $user
            ->setEmail(self::VALID_EMAIL_VALUE)
            ->setRoles(['USER'])
            ->setPassword(self::VALID_PASSWORD_VALUE)
            ->setFirstname(self::VALID_FIRSTNAME_VALUE)
            ->setLastname(self::VALID_LASTNAME_VALUE)
            ->setSubscription($subscription)
            ->setGoogleId('true google id')
            ->setBirthDate($birthdate)
            ->setResetToken('true token')
            ->addEvent($event)
            ->setAvatar('true avatar')
            ->addMessage($message)
            ->addNotice($notice);

        $this->assertTrue($user->getEmail() === self::VALID_EMAIL_VALUE);
        $this->assertTrue($user->getUsername() === self::VALID_EMAIL_VALUE);
        $this->assertContains('USER', $user->getRoles());
        $this->assertTrue($user->getPassword() === self::VALID_PASSWORD_VALUE);
        $this->assertTrue($user->getFirstname() === self::VALID_FIRSTNAME_VALUE);
        $this->assertTrue($user->getLastname() === self::VALID_LASTNAME_VALUE);
        $this->assertTrue($user->getSubscription() === $subscription);
        $this->assertTrue($user->getGoogleId() === 'true google id');
        $this->assertTrue($user->getBirthDate() === $birthdate);
        $this->assertTrue($user->getResetToken() === 'true token');
        $this->assertContains($event, $user->getEvents());
        $this->assertTrue($user->getAvatar() === 'true avatar');
        $this->assertContains($message, $user->getMessages());
        $this->assertContains($notice, $user->getNotices());
    }

    public function testIsFalse(): void
    {
        $user = new User();
        $subscription = new Subscription();
        $birthdate = new DateTime();
        $event = new Event();
        $message = new Message();
        $notice = new Notice();

        $user
            ->setEmail(self::VALID_EMAIL_VALUE)
            ->setRoles(['USER'])
            ->setPassword(self::VALID_PASSWORD_VALUE)
            ->setFirstname(self::VALID_FIRSTNAME_VALUE)
            ->setLastname(self::VALID_LASTNAME_VALUE)
            ->setSubscription($subscription)
            ->setGoogleId('true google id')
            ->setBirthDate($birthdate)
            ->setResetToken('true token')
            ->addEvent($event)
            ->setAvatar('true avatar')
            ->addMessage($message)
            ->addNotice($notice);

        $this->assertFalse($user->getEmail() === 'false@gmail.com');
        $this->assertFalse($user->getUsername() === 'false@gmail.com');
        $this->assertNotContains('FALSE', $user->getRoles());
        $this->assertFalse($user->getPassword() === 'falsePassword1');
        $this->assertFalse($user->getFirstname() === 'false firstname');
        $this->assertFalse($user->getLastname() === 'false lastname');
        $this->assertFalse($user->getSubscription() === new Subscription());
        $this->assertFalse($user->getGoogleId() === 'false google id');
        $this->assertFalse($user->getBirthDate() === new DateTime());
        $this->assertFalse($user->getResetToken() === 'false token');
        $this->assertNotContains(new Event(), $user->getEvents());
        $this->assertFalse($user->getAvatar() === 'false avatar');
        $this->assertNotContains(new Message(), $user->getMessages());
        $this->assertNotContains(new Notice(), $user->getNotices());
    }

    public function testIsEmpty(): void
    {
        $user = new User();

        $this->assertEmpty($user->getEmail());
        $this->assertContainsEquals('ROLE_USER', $user->getRoles());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getFirstname());
        $this->assertEmpty($user->getLastname());
        $this->assertEmpty($user->getSubscription());
        $this->assertEmpty($user->getGoogleId());
        $this->assertEmpty($user->getBirthDate());
        $this->assertEmpty($user->getResetToken());
        $this->assertEmpty($user->getEvents());
        $this->assertEmpty($user->getAvatar());
        $this->assertEmpty($user->getMessages());
        $this->assertEmpty($user->getNotices());
        $this->assertEmpty($user->getId());
    }

    public function testRemoveCollectionElement(): void
    {
        $user = new User();
        $event = new Event();
        $message = new Message();
        $notice = new Notice();

        $user
            ->addEvent($event)
            ->addMessage($message)
            ->addNotice($notice)
            ->removeEvent($event)
            ->removeMessage($message)
            ->removeNotice($notice);

        $this->assertNotContains($event, $user->getEvents());
        $this->assertNotContains($message, $user->getMessages());
        $this->assertNotContains($notice, $user->getNotices());
    }

    public function testUserEntityIsValid(): void
    {
        $user = new User();

        $user
            ->setEmail(self::VALID_EMAIL_VALUE)
            ->setPassword(self::VALID_PASSWORD_VALUE)
            ->setFirstname(self::VALID_FIRSTNAME_VALUE)
            ->setLastname(self::VALID_LASTNAME_VALUE)
            ->setSubscription(new Subscription())
            ->setBirthDate(new DateTime('1998-07-02'));

        $this->getValidationErrors($user, 0);
    }

    public function testUserEntityIsInvalidBecauseNoEmailEntered(): void
    {
        $user = new User();

        $user
            ->setPassword(self::VALID_PASSWORD_VALUE)
            ->setFirstname(self::VALID_FIRSTNAME_VALUE)
            ->setLastname(self::VALID_LASTNAME_VALUE)
            ->setSubscription(new Subscription())
            ->setBirthDate(new DateTime('1998-07-02'));

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoFirstnameEntered(): void
    {
        $user = new User();

        $user
            ->setEmail(self::VALID_EMAIL_VALUE)
            ->setPassword(self::VALID_PASSWORD_VALUE)
            ->setLastname(self::VALID_LASTNAME_VALUE)
            ->setSubscription(new Subscription())
            ->setBirthDate(new DateTime('1998-07-02'));

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoLastnameEntered(): void
    {
        $user = new User();

        $user
            ->setEmail(self::VALID_EMAIL_VALUE)
            ->setPassword(self::VALID_PASSWORD_VALUE)
            ->setFirstname(self::VALID_FIRSTNAME_VALUE)
            ->setSubscription(new Subscription())
            ->setBirthDate(new DateTime('1998-07-02'));

            $errors = $this->getValidationErrors($user, 1);

            $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoBirthdateEntered(): void
    {
        $user = new User();

        $user
        ->setEmail(self::VALID_EMAIL_VALUE)
        ->setPassword(self::VALID_PASSWORD_VALUE)
        ->setFirstname(self::VALID_FIRSTNAME_VALUE)
        ->setLastname(self::VALID_LASTNAME_VALUE)
        ->setSubscription(new Subscription());

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseInvalidBirthdateEntered(): void
    {
        $user = new User();

        $user
        ->setEmail(self::VALID_EMAIL_VALUE)
        ->setPassword(self::VALID_PASSWORD_VALUE)
        ->setFirstname(self::VALID_FIRSTNAME_VALUE)
        ->setLastname(self::VALID_LASTNAME_VALUE)
        ->setSubscription(new Subscription())
        ->setBirthDate(new DateTime('2018-07-02')); // less than 18 years old

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::BIRTHDATE_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoPasswordEntered(): void
    {
        $user = new User();

        $user
        ->setEmail(self::VALID_EMAIL_VALUE)
        ->setFirstname(self::VALID_FIRSTNAME_VALUE)
        ->setLastname(self::VALID_LASTNAME_VALUE)
        ->setSubscription(new Subscription())
        ->setBirthDate(new DateTime('1998-07-02'));

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseAnInvalidEmailEntered(): void
    {
        $user = new User();

        $user
            ->setEmail(self::INVALID_EMAIL_VALUE)
            ->setPassword(self::VALID_PASSWORD_VALUE)
            ->setFirstname(self::VALID_FIRSTNAME_VALUE)
            ->setLastname(self::VALID_LASTNAME_VALUE)
            ->setSubscription(new Subscription())
            ->setBirthDate(new DateTime('1998-07-02'));

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::EMAIL_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoSubscriptionEntered(): void
    {
        $user = new User();

        $user
            ->setEmail(self::VALID_EMAIL_VALUE)
            ->setPassword(self::VALID_PASSWORD_VALUE)
            ->setFirstname(self::VALID_FIRSTNAME_VALUE)
            ->setLastname(self::VALID_LASTNAME_VALUE)
            ->setBirthDate(new DateTime('1998-07-02'));

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    private function getValidationErrors(User $user, int $numberOfExpectedErrors): ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($user);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }
}
