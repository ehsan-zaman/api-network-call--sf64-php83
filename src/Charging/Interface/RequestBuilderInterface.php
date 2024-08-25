<?php

namespace App\Charging\Interface;

use App\Interface\Arrayable;

interface RequestBuilderInterface
{
    public function build(Arrayable $arrayable): RequestInterface;
}
