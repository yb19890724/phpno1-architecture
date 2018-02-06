<?php

namespace {namespace};

use {repository_namespace};

class {upper_name}Service extends AbstractService
{
    protected ${name}Repository;

    public function __construct({upper_name}Repository ${name}Repository)
    {
        $this->{name}Repository = ${name}Repository;
    }

{resource_method}
}