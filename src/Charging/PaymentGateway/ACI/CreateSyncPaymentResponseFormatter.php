<?php

namespace App\Charging\PaymentGateway\ACI;

use DateTimeImmutable;
use DateTimeZone;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Main responsibility - to format response to a specific structure
 */
class CreateSyncPaymentResponseFormatter extends ACIAbstractResponseFormatter
{
    public function format(ResponseInterface $response): array
    {
        $responseArray = parent::format($response);

        return [
            'transactionId' => $responseArray['id'],
            'dateOfCreation' => DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s.vO', $responseArray['timestamp']
                )->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s.vO'),
            'amount' => $responseArray['amount'],
            'currency' => $responseArray['currency'],
            'cardBin' => $responseArray['card']['bin'],
        ];
    }
}
