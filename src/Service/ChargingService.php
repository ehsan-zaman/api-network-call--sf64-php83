<?php

namespace App\Service;

use App\Object\DTO\ChargingRequestDTO;
use App\Charging\Client;
use App\Charging\PaymentGateway\ACI\CreateSyncPaymentRequest;
use App\Charging\PaymentGateway\ACI\CreateSyncPaymentResponseFormatter;
use App\Charging\PaymentGateway\Shift4\CreateChargeRequest;
use App\Charging\PaymentGateway\Shift4\CreateChargeResponseFormatter;
use App\Charging\Object\PaymentGateway;
use InvalidArgumentException;

class ChargingService
{
    /**
     * Performs the charging using the gateway and charging related information provided
     *
     * @param ChargingRequestDTO $chargingRequestDTO - charging related information
     * @param PaymentGateway $paymentGateway - payment gateway short name
     * @return array
     */
    public function charge(ChargingRequestDTO $chargingRequestDTO, PaymentGateway $paymentGateway): array
    {
        switch ($paymentGateway) {
            case PaymentGateway::ACI:
                return $this->chargeUsingACI($chargingRequestDTO);

            case PaymentGateway::SHIFT4:
                return $this->chargeUsingShift4($chargingRequestDTO);

            default:
                throw new InvalidArgumentException("$paymentGateway is not supported yet.");
        }
    }

    /**
     * Performs charging using ACI and charging related information provided
     * In this method - entity id, currency, payment brand, payment type, card holder name are hard coded because in the
     * task description they are allowed to be like this for test environment
     *
     * @param ChargingRequestDTO $chargingRequestDTO - charging related information
     * @return array
     */
    public function chargeUsingACI(ChargingRequestDTO $chargingRequestDTO): array
    {
        $chargeRequest = (new CreateSyncPaymentRequest())
            ->setEntityId('8a8294174b7ecb28014b9699220015ca')
            ->setAmount($chargingRequestDTO->getAmount())
            ->setCurrency('EUR')
            ->setPaymentBrand('VISA')
            ->setPaymentType('DB')
            ->setCardNumber($chargingRequestDTO->getCardNumber())
            ->setCardHolder('Jane Jones')
            ->setCardExpMonth($chargingRequestDTO->getCardExpMonth())
            ->setCardExpYear($chargingRequestDTO->getCardExpYear())
            ->setCardCvv($chargingRequestDTO->getCvv())
            ->buildParameters();

        $client = new Client(new CreateSyncPaymentResponseFormatter());
        return $client->send($chargeRequest)->toArray();
    }

    /**
     * Performs charging using Shift4 and charging related information provided
     * Tto use shift4's charging endpoint, 2 more requests are needed to create the identifiers. And also, in the task
     * description, it is allowed to hard code the card. For these reasons, In this method - no arguments other than
     * amount and currency are being used from the request
     *
     * @param ChargingRequestDTO $chargingRequestDTO - charging related information
     * @return array
     */
    public function chargeUsingShift4(ChargingRequestDTO $chargingRequestDTO): array
    {
        $chargeRequest = (new CreateChargeRequest())
            ->setAmount($chargingRequestDTO->getAmount())
            ->setCurrency($chargingRequestDTO->getCurrency())
            ->setCustomerId('cust_vou7YTRiGxdykgxZKwEa503q')
            ->setCard('card_MzfA5HPV9PWVcxAymNNyqbnw')
            ->buildParameters();

        $client = new Client(new CreateChargeResponseFormatter());
        return $client->send($chargeRequest)->toArray();
    }
}
