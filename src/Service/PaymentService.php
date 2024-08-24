<?php

namespace App\Service;

use App\Object\DTO\PaymentRequestDTO;
use App\Payment\Client;
use App\Payment\Gateway\ACI\CreateSyncPaymentRequest;
use App\Payment\Gateway\ACI\CreateSyncPaymentResponseFormatter;
use App\Payment\Gateway\Shift4\CreateChargeRequest;
use App\Payment\Gateway\Shift4\CreateChargeResponseFormatter;
use App\Payment\Object\PaymentGateway;
use InvalidArgumentException;

class PaymentService
{
    /**
     * Performs the paymnent transaction using the gateway and payment related information provided
     *
     * @param PaymentRequestDTO $paymentRequestDTO - payment related information
     * @param PaymentGateway $paymentGateway - payment gateway short name
     * @return array
     */
    public function charge(PaymentRequestDTO $paymentRequestDTO, PaymentGateway $paymentGateway): array
    {
        switch ($paymentGateway) {
            case PaymentGateway::ACI:
                return $this->chargeUsingACI($paymentRequestDTO);

            case PaymentGateway::SHIFT4:
                return $this->chargeUsingShift4($paymentRequestDTO);

            default:
                throw new InvalidArgumentException("$paymentGateway is not supported yet.");
        }
    }

    /**
     * Performs payment transaction using ACI and payment related information provided
     * In this method - entity id, currency, payment brand, payment type, card holder name are hard coded because in the
     * task description they are allowed to be like this for test environment
     *
     * @param PaymentRequestDTO $paymentRequestDTO - payment related information
     * @return array
     */
    public function chargeUsingACI(PaymentRequestDTO $paymentRequestDTO): array
    {
        $chargeRequest = (new CreateSyncPaymentRequest())
            ->setEntityId('8a8294174b7ecb28014b9699220015ca')
            ->setAmount($paymentRequestDTO->getAmount())
            ->setCurrency('EUR')
            ->setPaymentBrand('VISA')
            ->setPaymentType('DB')
            ->setCardNumber($paymentRequestDTO->getCardNumber())
            ->setCardHolder('Jane Jones')
            ->setCardExpMonth($paymentRequestDTO->getCardExpMonth())
            ->setCardExpYear($paymentRequestDTO->getCardExpYear())
            ->setCardCvv($paymentRequestDTO->getCvv())
            ->buildParameters();

        $client = new Client(new CreateSyncPaymentResponseFormatter());
        return $client->send($chargeRequest);
    }

    /**
     * Performs payment transaction using Shift4 and payment related information provided
     * Tto use shift4's charging endpoint, 2 more requests are needed to create the identifiers. And also, in the task
     * description, it is allowed to hard code the card. For these reasons, In this method - no arguments other than
     * amount and currency are being used from the request
     *
     * @param PaymentRequestDTO $paymentRequestDTO - payment related information
     * @return array
     */
    public function chargeUsingShift4(PaymentRequestDTO $paymentRequestDTO)
    {
        $chargeRequest = (new CreateChargeRequest())
            ->setAmount($paymentRequestDTO->getAmount())
            ->setCurrency($paymentRequestDTO->getCurrency())
            ->setCustomerId('cust_vou7YTRiGxdykgxZKwEa503q')
            ->setCard('card_MzfA5HPV9PWVcxAymNNyqbnw')
            ->buildParameters();

        $client = new Client(new CreateChargeResponseFormatter());
        return $client->send($chargeRequest);
    }
}
