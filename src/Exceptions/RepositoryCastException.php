<?php

namespace Phpno1\Repository\Exceptions;

use Exception;

/**
 * 仓储类型转换异常
 * RepositoryCastException class
 */
class RepositoryCastException extends Exception
{
    const ErrorCode = 3004;

    public function __construct(string $message = null)
    {
        $message = $message ?? trans('repository.' . static::ErrorCode);
        parent::__construct($message, static::ErrorCode);
    }
}