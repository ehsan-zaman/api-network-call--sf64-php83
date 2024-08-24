<?php

namespace App\Payment\Gateway\Shift4;

use App\Payment\AbstractRequest;

abstract class Shift4AbstractRequest extends AbstractRequest
{
    protected array $parameters = [];

    public function getOptions(): array
    {
        return [
            'auth_basic' => ['pr_test_tXHm9qV9qV9bjIRHcQr9PLPa', ''],
            'body' => $this->parameters
        ];
    }

    public abstract function buildParameters(): Shift4AbstractRequest;
}
