<?php

namespace {namespace};

use {repository_injection};

class {class_name}Service
{
    /**
     * @var {class_name}RepositoryEloquent
     */
    protected ${var_name}Repository;

    public function __construct({class_name}Repository ${var_name}Repository)
    {
        $this->{var_name}Repository = ${var_name}Repository;
    }

{method}
}