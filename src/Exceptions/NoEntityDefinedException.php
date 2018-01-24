<?php

namespace Phpno1\Repository\Exceptions;

use Exception;

/**
 * 模型实例没有找到
 * NoEntityDefined class
 */
class NoEntityDefinedException extends Exception
{
    const ErrorCode = 3001;

    public function __construct(string $message = null)
    {
        $message = $message ?? trans('repository.' . static::ErrorCode);
        parent::__construct($message, static::ErrorCode);
    }
}
