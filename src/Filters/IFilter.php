<?php

namespace Phpno1\Architecture\Filters;

/**
 * 过滤操作接口
 * IFilter interface
 */
interface IFilter 
{
    public function filter($entity, $value);
}