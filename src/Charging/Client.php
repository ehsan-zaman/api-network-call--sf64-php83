<?php

namespace App\Charging;

use App\Charging\Interface\RequestInterface;
use App\Charging\Interface\ResponseFormatterInterface;
use App\Interface\Arrayable;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Main responsibility - performing network call
 */
class Client
{
    public function __construct(private ResponseFormatterInterface $responseFormatter)
    {}

    public function send(RequestInterface $request): Arrayable
    {
        $response = HttpClient::create()->request(
            $request->getMethod(),
            $request->getUrl(),
            $request->getOptions()
        );

        return $this->responseFormatter->format($response);
    }
}
