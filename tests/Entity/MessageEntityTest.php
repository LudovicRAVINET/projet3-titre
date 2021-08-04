<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Message;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessageEntityTest extends KernelTestCase
{
    private const NOT_BLANK_CONSTRAINT_MESSAGE = 'Veuillez saisir une valeur.';
    private const DATE_CONSTRAINT_MESSAGE =
        'La date du commentaire doit être inférieure ou égale à la date de sa saisie.';
    private const VALID_COMMENT_VALUE = 'bon commentaire';
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
        $message = new Message();
        $user = new User();
        $date = new DateTime();
        $event = new Event();

        $message
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setDatetime($date)
            ->setUrl('true url')
            ->setEvent($event)
            ->setUser($user);

        $this->assertTrue($message->getComment() === self::VALID_COMMENT_VALUE);
        $this->assertTrue($message->getDatetime() === $date);
        $this->assertTrue($message->getUrl() === 'true url');
        $this->assertTrue($message->getUser() === $user);
        $this->assertTrue($message->getEvent() === $event);
    }

    public function testIsFalse(): void
    {
        $message = new Message();
        $user = new User();
        $date = new DateTime();
        $event = new Event();

        $message
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setDatetime($date)
            ->setUrl('true url')
            ->setEvent($event)
            ->setUser($user);

        $this->assertFalse($message->getComment() === 'false comment');
        $this->assertFalse($message->getDatetime() === new DateTime());
        $this->assertFalse($message->getUrl() === 'false url');
        $this->assertFalse($message->getUser() === new User());
        $this->assertFalse($message->getEvent() === new Event());
    }

    public function testIsEmpty(): void
    {
        $message = new Message();

        $this->assertEmpty($message->getComment());
        $this->assertEmpty($message->getDatetime());
        $this->assertEmpty($message->getUrl());
        $this->assertEmpty($message->getEvent());
        $this->assertEmpty($message->getId());
    }

    public function testMessageEntityIsValid(): void
    {
        $message = new Message();

        $message
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setDatetime(new DateTime('now'))
            ->setEvent(new Event())
            ->setUser(new User());

        $this->getValidationErrors($message, 0);
    }

    public function testMessageEntityIsInvalidBecauseNoCommentEntered(): void
    {
        $message = new Message();

        $message
            ->setDatetime(new DateTime('now'))
            ->setEvent(new Event())
            ->setUser(new User());

        $errors = $this->getValidationErrors($message, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testMessageEntityIsInvalidBecauseNoDateEntered(): void
    {
        $message = new Message();

        $message
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setEvent(new Event())
            ->setUser(new User());

        $errors = $this->getValidationErrors($message, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testMessageEntityIsInvalidBecauseNoEventEntered(): void
    {
        $message = new Message();

        $message
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setDatetime(new DateTime('now'))
            ->setUser(new User());

        $errors = $this->getValidationErrors($message, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testMessageEntityIsInvalidBecauseNoUserEntered(): void
    {
        $message = new Message();

        $message
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setDatetime(new DateTime('now'))
            ->setEvent(new Event());

        $errors = $this->getValidationErrors($message, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testMessageEntityIsInvalidBecauseInvalidDateEntered(): void
    {
        $message = new Message();

        $message
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setDatetime(new DateTime("+1 day"))
            ->setEvent(new Event())
            ->setUser(new User());

        $errors = $this->getValidationErrors($message, 1);

        $this->assertEquals(self::DATE_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    private function getValidationErrors(
        Message $message,
        int $numberOfExpectedErrors
    ): ConstraintViolationListInterface {
        $errors = $this->validator->validate($message);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }
}
