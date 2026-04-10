<?php

namespace App\Services\Teramedik\Exceptions;

class TeramedikAuthException extends TeramedikApiException
{
    public function __construct(string $message = 'Authentication failed', int $httpStatus = 401)
    {
        parent::__construct($message, $httpStatus);
    }
}
