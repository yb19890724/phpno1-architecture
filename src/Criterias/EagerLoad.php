<?php

namespace Phpno1\Repository\Criterias;

/**
 * EagerLoad class
 */
class EagerLoad implements ICriteria
{
    protected $relations;

    public function __construct(array $relations)
    {
        $this->relations = $relations;
    }

    public function apply($entity)
    {
        return $entity->with($this->relations);   
    }
}