<?php

namespace Phpno1\Repositories\Eloquent;


use Phpno1\Repositories\Criterias\ICriteria;
use Phpno1\Repositories\Contracts\IRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\{
    Builder, Model, ModelNotFoundException
};

use Phpno1\Repositories\Exceptions\{
    RepositoryCastFailException,
    NoEntityDefinedException,
    IllegalCriteriaInstanceException,
    NotEnoughWhereParamsException
};

use Illuminate\Support\Collection;

/**
 * 仓储基本操作抽象
 * AbstractRepository class
 */
abstract class AbstractRepository implements IRepository
{
    /**
     * 当前模型对象
     *
     * @var
     */
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();
        $this->boot();
    }

    /**
     * 初始化加载器，子类重写后使用
     * 
     * @return void
     */
    public function boot()
    {
    }

    /**
     * 获取模型的所有数据
     *
     * @return Collection
     */
    public function all()
    {
        return $this->entity->get();
    }

    /**
     * 获取第一行数据
     *
     * @return Model
     */
    public function first()
    {
        return $this->entity->first();
    }

    /**
     * 统计记录总数
     *
     * @return int
     */
    public function count()
    {
        return $this->entity->count();
    }

    /**
     * 根据where条件获取记录数
     *
     * @param [type] ...$condition
     * @return integer
     */
    public function findWhereCount(...$condition)
    {
        return $this->setWhere($condition)->count();
    }

    /**
     * 根据id获取一条数据
     *
     * @param integer $id
     * @return Builder
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
     *
     * findWhere('id', '>', 29) 或者 findWhere(['id', '>', 29],...)
     * 
     * @param array ...$condition
     * @return Collection
     */
    public function findWhere(...$condition)
    {
        return $this->setWhere($condition)->all();
    }

    /**
     * 获取一行记录
     * 参数同findWhere
     * 
     * @param [type] $column
     * @param [type] $value
     * @return Collection
     */
    public function findWhereFirst(...$condition)
    {
        $res = $this->setWhere($condition)->first();

        if (!$res) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel())
            );
        }

        return $res;
    }

    /**
     * 分页显示数据
     *
     * @param integer $perPage
     * @return LengthAwarePaginator
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
     * @return Model
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
     * @return boolean
     */
    public function update(int $id, array $properties)
    {
        return $this->find($id)->update($properties);
    }

    /**
     * 删除单条数据
     *
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }

    /**
     * 填充标准条件对象
     *
     * @param [type] ...$criteria
     * @return IRepository
     */
    public function withCriteria(...$criteria)
    {
        $criteria = array_flatten($criteria);
    
        foreach ($criteria as $item) {
            throw_if(
                !$item instanceof ICriteria,
                new IllegalCriteriaInstanceException()
            );
            $this->entity = $item->apply($this->entity);
        }

        return $this;
    }

    /**
     * 把仓储对象转换为Entity对象
     *
     * @return Builder
     */
    public function toEntity()
    {
        throw_if(
            !$this instanceof IRepository,
            new RepositoryCastFailException()
        );

        return $this->entity;
    }

    /**
     * 把entity对象转换为仓储对象。
     *
     * @param Builder $entity
     *
     * @return IRepository
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
     * @return IRepository
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
     * @return Builder
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
     * @return Builder | Model
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
