<?php

namespace Phpno1\Repository\Criterias;

/**
 * Class ByCreateTime
 * @package App\Repository\Criterias
 */
class ByCreateTime implements ICriteria
{
    public function apply($entity)
    {
        return $entity->latest();
    }
}
