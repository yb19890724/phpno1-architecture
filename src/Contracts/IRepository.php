<?php

namespace Phpno1\Repository\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 *  仓储抽象层
 *  IRepository interface
 */
interface IRepository
{
    public function entity();
    public function all();
    public function find($id);
    public function first();
    public function count();
    public function findWhere(...$condition);
    public function findWhereFirst(...$condition);
    public function findWhereCount(...$condition);
    public function paginate(int $perPage = 10);
    public function create(array $properties);
    public function update(int $id, array $properties);
    public function deleteById(int $id);
    public function withCriteria(...$criteria);
    public function toEntity();
    public function toRepository(Builder $entity);
}
