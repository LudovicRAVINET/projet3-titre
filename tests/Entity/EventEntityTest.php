<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Message;
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

    public function testIsTrue(): void
    {
        $event = new Event();
        $date = new DateTime();
        $time = new DateTime();
        $type = new Type();
        $user = new User();
        $message = new Message();

        $event
            ->setTitle(self::VALID_TITLE_VALUE)
            ->setDate($date)
            ->setTime($time)
            ->setImage('true image')
            ->setUser($user)
            ->setHasJackpot(true)
            ->setType($type)
            ->addMessage($message);

        $this->assertTrue($event->getTitle() === self::VALID_TITLE_VALUE);
        $this->assertTrue($event->getDate() === $date);
        $this->assertTrue($event->getTime() === $time);
        $this->assertTrue($event->getImage() === 'true image');
        $this->assertTrue($event->getUser() === $user);
        $this->assertTrue($event->getHasJackpot() === true);
        $this->assertTrue($event->getType() === $type);
        $this->assertContains($message, $event->getMessages());
    }

    public function testIsFalse(): void
    {
        $event = new Event();
        $date = new DateTime();
        $time = new DateTime();
        $type = new Type();
        $user = new User();
        $message = new Message();

        $event
            ->setTitle(self::VALID_TITLE_VALUE)
            ->setDate($date)
            ->setTime($time)
            ->setImage('true image')
            ->setUser($user)
            ->setHasJackpot(true)
            ->setType($type)
            ->addMessage($message);

        $this->assertFalse($event->getTitle() === 'false title');
        $this->assertFalse($event->getDate() === new DateTime());
        $this->assertFalse($event->getTime() === new DateTime());
        $this->assertFalse($event->getImage() === 'false image');
        $this->assertFalse($event->getUser() === new User());
        $this->assertFalse($event->getHasJackpot() === false);
        $this->assertFalse($event->getType() === new Type());
        $this->assertNotContains(new Message(), $event->getMessages());
    }

    public function testIsEmpty(): void
    {
        $event = new Event();

        $this->assertEmpty($event->getTitle());
        $this->assertEmpty($event->getDate());
        $this->assertEmpty($event->getTime());
        $this->assertEmpty($event->getImage());
        $this->assertEmpty($event->getUser());
        $this->assertEmpty($event->getHasJackpot());
        $this->assertEmpty($event->getType());
        $this->assertEmpty($event->getMessages());
        $this->assertEmpty($event->getId());
    }

    public function testRemoveCollectionElement(): void
    {
        $event = new Event();
        $message = new Message();

        $event
            ->addMessage($message)
            ->removeMessage($message);

        $this->assertNotContains($message, $event->getMessages());
    }

    public function testGetDateFr(): void
    {
        $event = new Event();
        $dateEn = new DateTime('2021-07-30');

        $this->assertTrue($event->setDate($dateEn)->getDateFr() === 'vendredi 30 juillet 2021');
        $this->assertFalse($event->setDate($dateEn)->getDateFr() === 'jeudi 29 juillet 2021');
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
