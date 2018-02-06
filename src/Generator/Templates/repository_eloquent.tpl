<?php

namespace {namespace};

use {model_namespace}\{upper_name};
use {interface_namespace}\{upper_name}Repository;

class {upper_name}RepositoryEloquent extends AbstractRepository implements {upper_name}Repository
{
    protected $filters = [
        // filter and sort settings
    ];

    public function entity()
    {
        return {upper_name}::class;
    }

{resource_method}
}