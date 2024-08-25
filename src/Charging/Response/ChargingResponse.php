<?php

namespace App\Charging\Response;

use App\Interface\Arrayable;
use DateTimeImmutable;
use DateTimeZone;

class ChargingResponse implements Arrayable
{
    private string $transactionId;

    private DateTimeImmutable $dateOfCreation;

    private float $amount;

    private string $currency;

    private string $cardBin;

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getDateOfCreation(): DateTimeImmutable
    {
        return $this->dateOfCreation;
    }

    public function setDateOfCreation(DateTimeImmutable $dateOfCreation): self
    {
        $this->dateOfCreation = $dateOfCreation;

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

    public function getCardBin(): string
    {
        return $this->cardBin;
    }

    public function setCardBin(string $cardBin): self
    {
        $this->cardBin = $cardBin;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'transactionId' => $this->getTransactionId(),
            'dateOfCreation' => $this->getDateOfCreation()
                ->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s.vO'),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'cardBin' => $this->getCardBin(),
        ];
    }
}
