<?php

namespace Phpno1\Repository\Criterias;

use Phpno1\Repository\Traits\FilterTrait;

/**
 * FilterRequest class
 */
class FilterRequest implements ICriteria
{
    use FilterTrait;

    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function apply($entity)
    {
        return $this->init($entity, $this->filters)
            ->doFilter()
            ->doOrder()
            ->getEntity();
    }
}