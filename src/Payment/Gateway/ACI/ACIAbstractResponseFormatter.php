<?php

namespace App\Payment\Gateway\ACI;

use App\Payment\Interface\ResponseFormatterInterface;
use InvalidArgumentException;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class ACIAbstractResponseFormatter implements ResponseFormatterInterface
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
            throw new InvalidArgumentException('Cannot process request at this moment. Check the arguments provided and try again. If the problem still persists, please contact with admin.');
        }

        return $responseArray;
    }
}
