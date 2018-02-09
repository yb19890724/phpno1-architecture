<?php

namespace Phpno1\Repositories\Criterias;

/**
 * Class ByCreateTime
 * @package App\Repositories\Criterias
 */
class ByCreateTime implements ICriteria
{
    public function apply($entity)
    {
        return $entity->latest();
    }
}
