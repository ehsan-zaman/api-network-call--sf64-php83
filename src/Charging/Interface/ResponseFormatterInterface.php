<?php

namespace App\Charging\Interface;

use App\Interface\Arrayable;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ResponseFormatterInterface
{
    public function format(ResponseInterface $response): array;
}
