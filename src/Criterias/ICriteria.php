<?php

namespace Phpno1\Repositories\Criterias;

/**
 * Interface ICriteria
 * @package Phpno1\Repositories\Criterias
 */
interface ICriteria
{
    public function apply($entity);
}
