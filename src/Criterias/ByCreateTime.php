<?php

namespace Phpno1\Architecture\Criterias;

/**
 * Class ByCreateTime
 * @package App\Architecture\Criterias
 */
class ByCreateTime implements ICriteria
{
    public function apply($entity)
    {
        return $entity->latest();
    }
}
