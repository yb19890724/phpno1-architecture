<?php

namespace Phpno1\Repositories\Traits;

use Phpno1\Repositories\Exceptions\IllegalFilterInstanceException;
use Phpno1\Repositories\Filters\{
    IFilter,
    IOrder
};

/**
 * 过滤操作挂件
 * FilterTrait trait
 */
trait FilterTrait
{
    /**
     * 设置过滤的模型
     *
     * @var array
     */
    protected $filterList = [];

    /**
     * 过滤配置列表
     *
     * @var array
     */
    protected $orderConfigs;

    /**
     * entity数据操作对象
     *
     * @var
     */
    protected $entity;

    /**
     * 初始化数据
     *
     * @param [type] $entity
     * @param [type] $filterList
     * @return $this
     */
    public function init($entity, $filterList)
    {
        $this->orderConfigs = config('repository.order');
        $this->entity = $entity;
        $this->filterList = $filterList;

        return $this;
    }

    /**
     * 执行过滤操作
     *
     * @return $this
     */
    public function doFilter()
    {
        foreach ($this->getSearchable() as $key => $item) {
            $this->entity = $this->resolveFilter($key)->filter($this->entity, $item);
        }

        return $this;
    }

    /**
     * 执行单字段排序操作
     *
     * @return $this
     */
    public function doOrder()
    {
        $orderInfo = $this->getOrderable();

        if (!empty($orderInfo)) {
            $key  = $orderInfo[$this->orderConfigs['field']];
            $type = $orderInfo[$this->orderConfigs['type']];
            $this->entity = $this->resolveOrder($key)->order($this->entity, $type);
        }

        return $this;
    }

    /**
     * 获取实例对象
     *
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * 实例化filter对象
     *
     * @param [type] $class
     * @return IFilter
     */
    protected function resolveFilter($filterName)
    {
        $filter = new $this->filters[$filterName]();
        throw_if(
            !$filter instanceof IFilter,
            new IllegalFilterInstanceException()
        );
        
        return $filter;
    }

    /**
     * 实例化order对象
     *
     * @param $orderName
     * @return mixed
     */
    protected function resolveOrder($orderName)
    {
        $order = new $this->filters[$orderName];

        throw_if(
            !$order instanceof IOrder,
            new IllegalFilterInstanceException()
        );

        return $order;
    }

    /**
     * 获取查询的键值对数据
     *
     * @return array
     */
    protected function getSearchable()
    {
        return array_filter(
            request()->only(array_keys($this->filterList))
        );
    }

    /**
     * 获取排序的键值对数据
     *
     * @return array
     */
    protected function getOrderable()
    {
        $list = array_filter(
            request()->only([$this->orderConfigs['field'], $this->orderConfigs['type']])
        );

        return count($list) === count($this->orderConfigs) ? $list : [];
    }
}