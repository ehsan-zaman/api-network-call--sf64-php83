<?php

namespace App\Charging\PaymentGateway\ACI;

/**
 * This class is like a data container for ACI's payment request
 */
class CreateSyncChargingRequest extends ACIAbstractRequest
{
    private string $entityId;

    private float $amount = 1;

    private string $currency = 'EUR';

    private string $paymentBrand = 'VISA';

    private string $paymentType = 'PA';

    private string $cardNumber = '';

    private string $cardHolder = '';

    private string $cardExpMonth = '';

    private string $cardExpYear = '';

    private string $cardCvv = '';

    public function __construct()
    {
        $this->setUrl('https://eu-test.oppwa.com/v1/payments')
            ->setMethod('POST');
    }

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function setEntityId(string $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPaymentBrand(): string
    {
        return $this->paymentBrand;
    }

    public function setPaymentBrand(string $paymentBrand): self
    {
        $this->paymentBrand = $paymentBrand;

        return $this;
    }

    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardHolder(): string
    {
        return $this->cardHolder;
    }

    public function setCardHolder(string $cardHolder): self
    {
        $this->cardHolder = $cardHolder;

        return $this;
    }

    public function getCardExpMonth(): string
    {
        return $this->cardExpMonth;
    }

    public function setCardExpMonth(string $cardExpMonth): self
    {
        $this->cardExpMonth = $cardExpMonth;

        return $this;
    }

    public function getCardExpYear(): string
    {
        return $this->cardExpYear;
    }

    public function setCardExpYear(string $cardExpYear): self
    {
        $this->cardExpYear = $cardExpYear;

        return $this;
    }

    public function getCardCvv(): string
    {
        return $this->cardCvv;
    }

    public function setCardCvv(string $cardCvv): self
    {
        $this->cardCvv = $cardCvv;

        return $this;
    }

    public function buildParameters(): self
    {
        $this->parameters = [
            'entityId' => $this->getEntityId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'paymentBrand' => $this->getPaymentBrand(),
            'paymentType' => $this->getPaymentType(),
            'card.number' => $this->getCardNumber(),
            'card.holder' => $this->getCardHolder(),
            'card.expiryMonth' => $this->getCardExpMonth(),
            'card.expiryYear' => $this->getCardExpYear(),
            'card.cvv' => $this->getCardCvv()
        ];

        return $this;
    }
}
