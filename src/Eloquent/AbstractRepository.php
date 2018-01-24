<?php

namespace Phpno1\Repository\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Phpno1\Repository\Criterias\ICriteria;
use Phpno1\Repository\Contracts\IRepository;
use Phpno1\Repository\Exceptions\RepositoryCastException;
use Phpno1\Repository\Exceptions\NoEntityDefinedException;
use Phpno1\Repository\Exceptions\NotCriteriaInstanceException;
use Phpno1\Repository\Exceptions\NotEnoughWhereParamsException;

/**
 * 仓储基本操作抽象
 * AbstractRepository class
 */
abstract class AbstractRepository implements IRepository
{
    /**
     * 当前模型对象
     *
     * @var [type]
     */
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();
        $this->boot();
    }

    public function boot()
    {
    }

    /**
     * 获取模型的所有数据
     *
     * @return void
     */
    public function all()
    {
        return $this->entity->get();
    }

    /**
     * 获取第一列数据
     *
     * @return void
     */
    public function first()
    {
        return $this->entity->first();
    }

    /**
     * 统计记录总数
     *
     * @return void
     */
    public function count()
    {
        return $this->entity->count();
    }

    /**
     * 根据where条件获取记录数
     *
     * @param [type] ...$condition
     * @return void
     */
    public function findWhereCount(...$condition)
    {
        throw_if(
            count($condition) < 2,
            new NotEnoughWhereParamsException()
        );

        return $this->setWhere($condition)->count();
    }

    /**
     * 根据id获取一条数据
     *
     * @param integer $id
     * @return void
     */
    public function find(int $id)
    {
        $model = $this->entity->find($id);

        throw_if(
            !$model,
            new ModelNotFoundException($this->entity->getModel(), $id)
        );

        return $model;
    }

    /**
     * 根据条件查询获取多行结果集
     * findWhere('id', '>', 29) 或者 findWhere(['id', '>', 29],...)
     * 
     * @param [type] ...$condition
     * @return void
     */
    public function findWhere(...$condition)
    {
        throw_if(
            count($condition) < 2,
            new NotEnoughWhereParamsException()
        );

        return $this->setWhere($condition)->all();
    }

    /**
     * 获取一行记录
     * 参数同findWhere
     * 
     * @param [type] $column
     * @param [type] $value
     * @return void
     */
    public function findWhereFirst(...$condition)
    {
        throw_if(
            count($condition) < 2,
            new NotEnoughWhereParamsException()
        );

        $model = $this->setWhere($condition)->first();

        if (!$model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel())
            );
        }

        return $model;
    }

    /**
     * 分页显示数据
     *
     * @param integer $perPage
     * @return void
     */
    public function paginate(int $perPage = 10)
    {
        $perPage = config('repository.pagination.limit') ?? $perPage;
        
        return $this->entity->paginate($perPage);
    }

    /**
     * 添加数据
     *
     * @param array $properties
     * @return void
     */
    public function create(array $properties)
    {
        return $this->entity->create($properties);
    }

    /**
     * 修改数据
     *
     * @param integer $id
     * @param array $properties
     * @return void
     */
    public function update(int $id, array $properties)
    {
        return $this->find($id)->update($properties);
    }

    /**
     * 删除单条数据
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }

    /**
     * 填充标准条件对象
     *
     * @param [type] ...$criteria
     * @return $this
     */
    public function withCriteria(...$criteria)
    {
        $criteria = array_flatten($criteria);
    
        foreach ($criteria as $item) {
            throw_if(
                !$item instanceof ICriteria,
                new NotCriteriaInstanceException()
            );
            $this->entity = $item->apply($this->entity);
        }

        return $this;
    }

    /**
     * 把仓储对象转换为Entity对象
     *
     * @return void
     */
    public function toEntity()
    {
        throw_if(
            !$this instanceof IRepository,
            new RepositoryCastException()
        );

        return $this->entity;
    }

    /**
     * 把entity对象转换为仓储对象。
     *
     * @return void
     */
    public function toRepository(Builder $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * where条件的组装
     * 
     * @param array $condition
     * @return void
     */
    protected function setWhere(array $condition)
    {
        foreach ($condition as $item) {
            if (!is_array($item)) {
                $this->entity = $this->setConditions($condition);
                break;
            }

            $this->entity = $this->setConditions($item);
        }

        return $this;
    }

    /**
     * 根据参数的类型来叠加where
     *
     * @param array $condition
     * @return
     */
    protected function setConditions(array $condition)
    {
        $count = count($condition);
        
        throw_if(
            $count < 2,
            new NotEnoughWhereParamsException()
        );
        
        return $this->entity->where(
            $condition[0], 
            $condition[1], 
            $condition[2] ?? null
        );
    }

    /**
     * 获取当前的model对象
     *
     * @return
     */
    protected function resolveEntity()
    {
        throw_if(
            !method_exists($this, 'entity'),
            new NoEntityDefinedException()
        );

        return app()->make($this->entity());
    }
}
