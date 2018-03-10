<?php

namespace Phpno1\Architecture\Criterias;

use Phpno1\Architecture\Traits\FilterTrait;

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