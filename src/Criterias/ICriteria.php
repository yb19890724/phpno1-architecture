<?php

namespace Phpno1\Repository\Criterias;

/**
 * 公共操作抽象层
 * ICriteria interface
 */
interface ICriteria
{
    public function apply($entity);
}
