<?php

namespace Phpno1\Repository\Criterias;

/**
 * Interface ICriteria
 * @package Phpno1\Repository\Criterias
 */
interface ICriteria
{
    public function apply($entity);
}
