<?php

namespace Phpno1\Repositories\Criterias;

/**
 * 公共操作接口
 * 
 * ICriteria interface
 */
interface ICriteria
{
    public function apply($entity);
}
