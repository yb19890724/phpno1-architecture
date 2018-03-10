<?php

namespace Phpno1\Architecture\Filters;

/**
 * 排序操作接口
 * IOrder interface
 */
interface IOrder 
{
    public function order($entity, $direction);
}