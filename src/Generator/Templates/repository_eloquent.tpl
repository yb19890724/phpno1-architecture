<?php

namespace {namespace_eloquent};

use {namespace_model}\{class_name};
use {namespace}\{class_name}Repository;

class {class_name}RepositoryEloquent extends AbstractRepository implements {class_name}Repository
{
    protected $filters = [
        // filter and sort settings
    ];

    public function entity()
    {
        return {class_name}::class;
    }
}