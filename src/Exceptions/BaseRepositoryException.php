<?php

namespace Phpno1\Repositories\Exceptions;

use Exception;

/**
 * 仓储异常父类
 * 
 * BaseRepositoryException class
 */
abstract class BaseRepositoryException extends Exception
{
    protected const LANGUAGE_FILE_NAME = 'repository';

    protected $snakeClassName;

    protected $errorCode = 0;

    public function __construct(string $message = null)
    {
        $this->coverCurrentClassNameToSnakeCase();
        $lang = static::LANGUAGE_FILE_NAME . '.' . $this->snakeClassName;
        $message = $message ?? __($lang);
        parent::__construct($message, $this->errorCode);
    }

    /**
     * 转换当前类名
     *
     * @return void
     */
    protected function coverCurrentClassNameToSnakeCase()
    {
        $name = snake_case(class_basename(get_class($this)));
        $this->snakeClassName = str_replace('_exception', '', $name);
    }
}
