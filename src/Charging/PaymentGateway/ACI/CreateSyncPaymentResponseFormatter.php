<?php

namespace App\Charging\PaymentGateway\ACI;

use App\Charging\Response\ChargingResponse;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Main responsibility - to format response to a specific structure
 */
class CreateSyncPaymentResponseFormatter extends ACIAbstractResponseFormatter
{
    public function format(ResponseInterface $response): ChargingResponse
    {
        $responseArray = $this->validateResponse($response);

        return (new ChargingResponse())
            ->setTransactionId($responseArray['id'])
            ->setDateOfCreation(DateTimeImmutable::createFromFormat('Y-m-d H:i:s.vO', $responseArray['timestamp']))
            ->setAmount($responseArray['amount'])
            ->setCurrency($responseArray['currency'])
            ->setCardBin($responseArray['card']['bin']);
    }
}
