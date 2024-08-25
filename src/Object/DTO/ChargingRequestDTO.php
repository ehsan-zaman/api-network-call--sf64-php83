<?php

namespace App\Object\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ChargingRequestDTO
{
    #[Assert\NotBlank(message: "'amount' must be provided and have a valid numeric value")]
    #[Assert\GreaterThan(0, message: "'amount' must be valid and greater than 0")]
    #[Assert\Regex(pattern: '/^\d+(\.\d+)*$/', message: "'amount' must have a valid numeric value")]
    private string $amount;

    #[Assert\NotBlank(message: "'currency' must be provided")]
    #[Assert\Choice(['USD', 'EUR'], message: "'currency' is not valid. Avaliable values: USD, EUR")]
    private string $currency;

    #[Assert\NotBlank(message: "'cardNumber' must be provided")]
    #[Assert\Regex(pattern: '/^\d{16}$/', message: "'cardNumber' must be of 16 digits")]
    private string $cardNumber;

    #[Assert\NotBlank(message: "'cardExpYear' must be provided")]
    #[Assert\Regex(pattern: '/^\d{4}$/', message: "'cardExpYear' must be of 4 digits")]
    private string $cardExpYear;

    #[Assert\NotBlank(message: "'cardExpMonth' must be provided")]
    #[Assert\Regex(pattern: '/^\d{2}$/', message: "'cardExpMonth' must be of 2 digits")]
    private string $cardExpMonth;

    #[Assert\NotBlank(message: "'cvv' must be provided")]
    #[Assert\Length(exactly: 3, exactMessage: "'cvv' must be of 3 digits")]
    private string $cvv;

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount = null): self
    {
        $this->amount = $amount ? (float) $amount : 0.0;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency = null): self
    {
        $this->currency = $currency ?? '';

        return $this;
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber = null): self
    {
        $this->cardNumber = $cardNumber ?? '';

        return $this;
    }

    public function getCardExpYear(): string
    {
        return $this->cardExpYear;
    }

    public function setCardExpYear(string $cardExpYear = null): self
    {
        $this->cardExpYear = $cardExpYear ?? '';

        return $this;
    }

    public function getCardExpMonth(): string
    {
        return $this->cardExpMonth;
    }

    public function setCardExpMonth(string $cardExpMonth = null): self
    {
        $this->cardExpMonth = str_pad($cardExpMonth ?? '', 2, STR_PAD_LEFT);

        return $this;
    }

    public function getCvv(): string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv = null): self
    {
        $this->cvv = $cvv ?? '';

        return $this;
    }
}
