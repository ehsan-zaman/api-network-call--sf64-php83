<?php

namespace App\Payment\Interface;

use App\Interface\Arrayable;

interface RequestBuilderInterface
{
    public function build(Arrayable $arrayable): RequestInterface;
}
