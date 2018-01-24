<?php

namespace Phpno1\Repository\Exceptions;

use Exception;

/**
 * Filter实例不合法异常
 * IllegalFilterInstanceException class
 */
class IllegalFilterInstanceException extends Exception
{
    const ErrorCode = 3005;

    public function __construct(string $message = null)
    {
        $message = $message ?? trans('repository.' . static::ErrorCode);
        parent::__construct($message, static::ErrorCode);
    }
}
