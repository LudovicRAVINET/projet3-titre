<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TypeEntityTest extends KernelTestCase
{
    private const NOT_BLANK_CONSTRAINT_MESSAGE = 'Veuillez saisir une valeur.';
    private const VALID_NAME_VALUE = 'wedding';
    private const VALID_DEFAULT_PICTURE_VALUE = 'wedding-picture.png';
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
        $type = new Type();
        $event = new Event();

        $type
            ->setName(self::VALID_NAME_VALUE)
            ->setDefaultPicture(self::VALID_DEFAULT_PICTURE_VALUE)
            ->addEvent($event);

        $this->assertTrue($type->getName() === self::VALID_NAME_VALUE);
        $this->assertTrue($type->getDefaultPicture() === self::VALID_DEFAULT_PICTURE_VALUE);
        $this->assertContains($event, $type->getEvents());
    }

    public function testIsFalse(): void
    {
        $type = new Type();
        $event = new Event();

        $type
            ->setName(self::VALID_NAME_VALUE)
            ->setDefaultPicture(self::VALID_DEFAULT_PICTURE_VALUE)
            ->addEvent($event);

        $this->assertFalse($type->getName() === 'false name');
        $this->assertFalse($type->getDefaultPicture() === 'false picture');
        $this->assertNotContains(new Event(), $type->getEvents());
    }

    public function testIsEmpty(): void
    {
        $type = new Type();

        $this->assertEmpty($type->getName());
        $this->assertEmpty($type->getDefaultPicture());
        $this->assertEmpty($type->getEvents());
        $this->assertEmpty($type->getId());
    }

    public function testRemoveCollectionElement(): void
    {
        $type = new Type();
        $event = new Event();

        $type
            ->addEvent($event)
            ->removeEvent($event);

        $this->assertNotContains($event, $type->getEvents());
    }

    public function testTypeEntityIsValid(): void
    {
        $type = new Type();

        $type
            ->setName(self::VALID_NAME_VALUE)
            ->setDefaultPicture(self::VALID_DEFAULT_PICTURE_VALUE);

        $this->getValidationErrors($type, 0);
    }

    public function testTypeEntityIsInvalidBecauseNoNameEntered(): void
    {
        $type = new Type();

        $type
            ->setDefaultPicture(self::VALID_DEFAULT_PICTURE_VALUE);

        $errors = $this->getValidationErrors($type, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testTypeEntityIsInvalidBecauseNoDefaultPictureEntered(): void
    {
        $type = new Type();

        $type
            ->setName(self::VALID_NAME_VALUE);

        $errors = $this->getValidationErrors($type, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    private function getValidationErrors(Type $type, int $numberOfExpectedErrors): ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($type);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }
}
