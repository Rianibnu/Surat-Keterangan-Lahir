<?php

namespace App\Services\Teramedik\Exceptions;

use Exception;

class TeramedikApiException extends Exception
{
    protected int $httpStatus;
    protected ?array $errorData;

    public function __construct(string $message = '', int $httpStatus = 500, ?array $errorData = null)
    {
        parent::__construct($message, $httpStatus);
        $this->httpStatus = $httpStatus;
        $this->errorData = $errorData;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    public function getErrorData(): ?array
    {
        return $this->errorData;
    }

    public function isClientError(): bool
    {
        return $this->httpStatus >= 400 && $this->httpStatus < 500;
    }

    public function isServerError(): bool
    {
        return $this->httpStatus >= 500;
    }
}
