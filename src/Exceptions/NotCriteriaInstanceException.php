<?php

namespace Phpno1\Repository\Exceptions;

use Exception;

/**
 * 模型实例没有找到
 * NotCriteriaInstanceException class
 */
class NotCriteriaInstanceException extends Exception
{
    const ErrorCode = 3002;

    public function __construct(string $message = null)
    {
        $message = $message ?? trans('repository.' . static::ErrorCode);
        parent::__construct($message, static::ErrorCode);
    }
}