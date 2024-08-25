<?php

namespace App\Charging;

use App\Charging\Interface\RequestInterface;

abstract class AbstractRequest implements RequestInterface
{
    private string $url;

    private string $method;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public abstract function getOptions(): array;
}
