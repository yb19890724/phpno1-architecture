<?php

namespace Phpno1\Repositories\Filters;

/**
 * 过滤操作接口
 * IFilter interface
 */
interface IFilter 
{
    public function filter($entity, $value);
}