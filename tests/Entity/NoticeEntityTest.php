<?php

namespace App\Tests\Entity;

use App\Entity\Notice;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NoticeEntityTest extends KernelTestCase
{
    private const NOT_BLANK_CONSTRAINT_MESSAGE = 'Veuillez saisir une valeur.';
    private const DATE_CONSTRAINT_MESSAGE =
        'La date du commentaire doit être inférieure ou égale à la date de sa saisie.';
    private const NOTE_CONSTRAINT_MESSAGE = 'La note doit être comprise entre 1 et 5.';
    private const VALID_NOTE_VALUE = 3;
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
        $notice = new Notice();
        $user = new User();
        $date = new DateTime();

        $notice
            ->setNote(self::VALID_NOTE_VALUE)
            ->setDate($date)
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setUser($user);

        $this->assertTrue($notice->getNote() === self::VALID_NOTE_VALUE);
        $this->assertTrue($notice->getdate() === $date);
        $this->assertTrue($notice->getComment() === self::VALID_COMMENT_VALUE);
        $this->assertTrue($notice->getUser() === $user);
    }

    public function testIsFalse(): void
    {
        $notice = new Notice();
        $user = new User();
        $date = new DateTime();

        $notice
            ->setNote(self::VALID_NOTE_VALUE)
            ->setDate($date)
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setUser($user);

        $this->assertFalse($notice->getNote() === 4);
        $this->assertFalse($notice->getdate() === new DateTime());
        $this->assertFalse($notice->getComment() === 'false comment');
        $this->assertFalse($notice->getUser() === new User());
    }

    public function testIsEmpty(): void
    {
        $notice = new Notice();

        $this->assertEmpty($notice->getNote());
        $this->assertEmpty($notice->getdate());
        $this->assertEmpty($notice->getComment());
        $this->assertEmpty($notice->getUser());
        $this->assertEmpty($notice->getId());
    }

    public function testNoticeEntityIsValid(): void
    {
        $notice = new Notice();

        $notice
            ->setNote(self::VALID_NOTE_VALUE)
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setUser(new User())
            ->setDate(new DateTime('now'));

        $this->getValidationErrors($notice, 0);
    }

    public function testNoticeEntityIsInvalidBecauseNoNoteEntered(): void
    {
        $notice = new Notice();

        $notice
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setUser(new User())
            ->setDate(new DateTime('now'));

        $errors = $this->getValidationErrors($notice, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testNoticeEntityIsInvalidBecauseNoCommentEntered(): void
    {
        $notice = new Notice();

        $notice
            ->setNote(self::VALID_NOTE_VALUE)
            ->setUser(new User())
            ->setDate(new DateTime('now'));

        $errors = $this->getValidationErrors($notice, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testNoticeEntityIsInvalidBecauseNoUserEntered(): void
    {
        $notice = new Notice();

        $notice
            ->setNote(self::VALID_NOTE_VALUE)
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setDate(new DateTime('now'));

        $errors = $this->getValidationErrors($notice, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testNoticeEntityIsInvalidBecauseNoDateEntered(): void
    {
        $notice = new Notice();

        $notice
            ->setNote(self::VALID_NOTE_VALUE)
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setUser(new User());

        $errors = $this->getValidationErrors($notice, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testNoticeEntityIsInvalidBecauseInvalidDateEntered(): void
    {
        $notice = new Notice();

        $notice
            ->setNote(self::VALID_NOTE_VALUE)
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setUser(new User())
            ->setDate(new DateTime("+1 day"));

        $errors = $this->getValidationErrors($notice, 1);

        $this->assertEquals(self::DATE_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    /**
     * @dataProvider provideInvalidNotes
     */
    public function testNoticeEntityIsInvalidBecauseInvalidNoteEntered(int $invalidNote): void
    {
        $notice = new Notice();

        $notice
            ->setNote($invalidNote)
            ->setComment(self::VALID_COMMENT_VALUE)
            ->setUser(new User())
            ->setDate(new DateTime('now'));

        $errors = $this->getValidationErrors($notice, 1);

        $this->assertEquals(self::NOTE_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function provideInvalidNotes(): array
    {
        return [
            [-2],
            [0],
            [6]
        ];
    }

    private function getValidationErrors(Notice $notice, int $numberOfExpectedErrors): ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($notice);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }
}
