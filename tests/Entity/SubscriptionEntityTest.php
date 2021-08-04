<?php

namespace App\Tests\Entity;

use App\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionEntityTest extends KernelTestCase
{
    private const NOT_BLANK_CONSTRAINT_MESSAGE = 'Veuillez saisir une valeur.';
    private const VALID_NAME_VALUE = 'PREMIUM';
    private const VALID_DESCRIPTION_VALUE = 'premium';
    private const VALID_PRICE_VALUE = 29.90;
    private const INVALID_PRICE_MESSAGE = 'Le prix saisi n\'est pas valide.';
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

    public function testSubscriptionEntityIsValid(): void
    {
        $subscription = new Subscription();

        $subscription
            ->setName(self::VALID_NAME_VALUE)
            ->setDescription(self::VALID_DESCRIPTION_VALUE)
            ->setPrice(self::VALID_PRICE_VALUE);

        $this->getValidationErrors($subscription, 0);
    }

    public function testSubscriptionEntityIsInvalidBecauseNoNameEntered(): void
    {
        $subscription = new Subscription();

        $subscription
            ->setDescription(self::VALID_DESCRIPTION_VALUE)
            ->setPrice(self::VALID_PRICE_VALUE);

        $errors = $this->getValidationErrors($subscription, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testSubscriptionEntityIsInvalidBecauseNoDescriptionEntered(): void
    {
        $subscription = new Subscription();

        $subscription
            ->setName(self::VALID_NAME_VALUE)
            ->setPrice(self::VALID_PRICE_VALUE);

        $errors = $this->getValidationErrors($subscription, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testSubscriptionEntityIsInvalidBecauseNoPriceEntered(): void
    {
        $subscription = new Subscription();

        $subscription
            ->setName(self::VALID_NAME_VALUE)
            ->setDescription(self::VALID_DESCRIPTION_VALUE);

        $errors = $this->getValidationErrors($subscription, 1);

        $this->assertEquals(self::NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    private function getValidationErrors(
        Subscription $subscription,
        int $numberOfExpectedErrors
    ): ConstraintViolationListInterface {
        $errors = $this->validator->validate($subscription);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }
}
