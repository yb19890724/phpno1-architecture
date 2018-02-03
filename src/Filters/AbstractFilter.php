<?php

namespace Phpno1\Repositories\Filters;

/**
 * 过滤基类
 * AbstractFilter class
 */
abstract class AbstractFilter implements IFilter
{
    /**
     * 字段映射设置
     *
     * @return array
     */
    protected function mappings()
    {
        return [];
    }

    /**
     * 过滤字段映射
     *
     * @param [type] $value
     * @return array
     */
    protected function resolveFilterValue($value)
    {
        return array_get($this->mappings(), $value);
    }

    /**
     * 排序规则映射
     *
     * @param [type] $direction
     * @return array
     */
    protected function resolveOrderDirection($direction)
    {
        return array_get([
            'desc' => 'desc',
            'asc' => 'asc'
        ], $direction, 'desc');
    }
}