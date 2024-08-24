<?php

namespace App\Payment\Gateway\Shift4;

use App\Payment\Interface\ResponseFormatterInterface;
use InvalidArgumentException;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class Shift4AbstractResponseFormatter implements ResponseFormatterInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function format(ResponseInterface $response): array
    {
        $responseArray = $response->toArray(false);
        $statusCode = $response->getStatusCode();
        $statusCodeCategory = substr($statusCode, 0, 1);

        if (in_array($statusCodeCategory, ['4', '5'])) {
            throw new InvalidArgumentException('Cannot process request at this moment. Please contact API admin');
        }

        return $responseArray;
    }
}
