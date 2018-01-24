<?php

namespace Phpno1\Repository\Exceptions;

use Exception;

/**
 * 模型实例没有找到
 * NotEnoughWhereParamsException class
 */
class NotEnoughWhereParamsException extends Exception
{
    const ErrorCode = 3003;

    public function __construct(string $message = null)
    {
        $message = $message ?? trans('repository.' . static::ErrorCode);
        parent::__construct($message, static::ErrorCode);
    }
}