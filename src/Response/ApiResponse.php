<?php

namespace App\Response;

use JsonSerializable;

/**
 * Main responsibility - providiing a specific structure for responses
 */
class ApiResponse implements JsonSerializable
{
    public const STATUS_OK = 'ok';
    public const STATU_ERROR = 'error';

    public function __construct(private string $status, private array $data = [], private array $errors = []) {}

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors($errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'status' => $this->status,
            'data' => $this->data,
            'errors' => $this->errors
        ];
    }
}
