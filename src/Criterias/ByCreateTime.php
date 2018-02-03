<?php

namespace Phpno1\Repositories\Criterias;

class ByCreateTime implements ICriteria
{
    public function apply($entity)
    {
        return $entity->latest();   
    }
}
