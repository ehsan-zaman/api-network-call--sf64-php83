<?php

namespace App\Charging\PaymentGateway\Shift4;

use DateTimeImmutable;
use DateTimeZone;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Main responsibility - to format response to a specific structure
 */
class CreateChargeResponseFormatter extends Shift4AbstractResponseFormatter
{
    public function format(ResponseInterface $response): array
    {
        $responseArray = parent::format($response);

        return [
            'transactionId' => $responseArray['id'],
            'dateOfCreation' => (new DateTimeImmutable('@'.$responseArray['created']))
                ->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s.vO'),
            'amount' => $responseArray['amount'],
            'currency' => $responseArray['currency'],
            'cardBin' => $responseArray['card']['first6'],
        ];
    }
}
