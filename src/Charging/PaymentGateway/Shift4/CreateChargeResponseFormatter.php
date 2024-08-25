<?php

namespace App\Charging\PaymentGateway\Shift4;

use App\Charging\Response\ChargingResponse;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Main responsibility - to format response to a specific structure
 */
class CreateChargeResponseFormatter extends Shift4AbstractResponseFormatter
{
    public function format(ResponseInterface $response): ChargingResponse
    {
        $responseArray = $this->validateResponse($response);

        return (new ChargingResponse())
            ->setTransactionId($responseArray['id'])
            ->setDateOfCreation(new DateTimeImmutable('@'.$responseArray['created']))
            ->setAmount($responseArray['amount'])
            ->setCurrency($responseArray['currency'])
            ->setCardBin($responseArray['card']['first6']);
    }
}
