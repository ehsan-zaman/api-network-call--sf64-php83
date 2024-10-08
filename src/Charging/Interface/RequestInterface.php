<?php

namespace App\Charging\Interface;

use App\Interface\Arrayable;

interface RequestInterface
{
    public function getUrl(): string;

    public function getMethod(): string;

    public function getOptions(): array;
}
