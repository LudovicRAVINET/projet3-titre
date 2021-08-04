<?php

namespace App\Tests\Entity;

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
