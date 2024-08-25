<?php

namespace App\Charging\PaymentGateway\ACI;

use App\Charging\AbstractRequest;

abstract class ACIAbstractRequest extends AbstractRequest
{
    protected array $parameters = [];

    public function getOptions(): array
    {
        return [
            'auth_bearer' => 'OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg=',
            'body' => $this->parameters
        ];
    }

    public abstract function buildParameters(): ACIAbstractRequest;
}
