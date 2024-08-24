<?php

namespace App\Payment\Gateway\Shift4;

/**
 * This class is like a data container for Shift4's create charge request
 */
class CreateChargeRequest extends Shift4AbstractRequest
{
    private float $amount = 1;

    private string $currency = 'EUR';

    private string $customerId = '';

    private string $card = '';

    public function __construct()
    {
        $this->setUrl('https://api.shift4.com/charges')
            ->setMethod('POST');
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

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getCard(): string
    {
        return $this->card;
    }

    public function setCard(string $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function buildParameters(): self
    {
        $this->parameters = [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'customerId' => $this->getCustomerId(),
            'card' => $this->getCard()
        ];

        return $this;
    }
}
