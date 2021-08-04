<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Type;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventEntityTest extends KernelTestCase
{
    private const NOT_BLANK_CONSTRAINT_MESSAGE = 'Veuillez saisir une valeur.';
    private const DATE_CONSTRAINT_MESSAGE =
        'La date de l\'événement doit être supérieure ou égale à la date de sa saisie.';
    private const VALID_TITLE_VALUE = 'bon titre';
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

    public function testEventEntityIsValid(): void
    {
        $event = new Event();

        $event
            ->setTitle(self::VALID_TITLE_VALUE)
            ->setDate(new DateTime('+10 days'))
            ->setUser(new User())
            ->setType(new Type());

        $this->getValidationErrors($event, 0);
    }

    public function testEventEntityIsInvalidBecauseNoTitleEntered(): void
    {
        $event = new Event();

        $event
            ->setDate(new DateTime('+10 days'))
            ->setUser(new User())
            ->setType(new Type());

        $errors = $this->getValidationErrors($event, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testEventEntityIsInvalidBecauseNoDateEntered(): void
    {
        $event = new Event();

        $event
            ->setTitle(self::VALID_TITLE_VALUE)
            ->setUser(new User())
            ->setType(new Type());

        $errors = $this->getValidationErrors($event, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testEventEntityIsInvalidBecauseNoUserEntered(): void
    {
        $event = new Event();

        $event
            ->setTitle(self::VALID_TITLE_VALUE)
            ->setDate(new DateTime('+10 days'))
            ->setType(new Type());

        $errors = $this->getValidationErrors($event, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testEventEntityIsInvalidBecauseNoTypeEntered(): void
    {
        $event = new Event();

        $event
            ->setTitle(self::VALID_TITLE_VALUE)
            ->setDate(new DateTime('+10 days'))
            ->setUser(new User());

        $errors = $this->getValidationErrors($event, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testEventEntityIsInvalidBecauseInvalidDateEntered(): void
    {
        $event = new Event();

        $event
            ->setTitle(self::VALID_TITLE_VALUE)
            ->setDate(new DateTime('-1 day'))
            ->setUser(new User())
            ->setType(new Type());

        $errors = $this->getValidationErrors($event, 1);

        $this->assertEquals(self::DATE_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }


    private function getValidationErrors(Event $event, int $numberOfExpectedErrors): ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($event);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }
}
