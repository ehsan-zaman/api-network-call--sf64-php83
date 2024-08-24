<?php

namespace App\Payment;

use App\Payment\Interface\RequestInterface;
use App\Payment\Interface\ResponseFormatterInterface;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Main responsibility - performing network call
 */
class Client
{
    public function __construct(private ResponseFormatterInterface $responseFormatter)
    {}

    public function send(RequestInterface $request): array
    {
        $response = HttpClient::create()->request(
            $request->getMethod(),
            $request->getUrl(),
            $request->getOptions()
        );

        return $this->responseFormatter->format($response);
    }
}
