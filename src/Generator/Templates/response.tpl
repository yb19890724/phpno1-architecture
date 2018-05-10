<?php

namespace {namespace};

use Illuminate\Contracts\Support\Responsable;

class {class_name} implements Responsable
{
    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function toResponse($request)
    {
        return $this->transform();
    }

    protected function transform()
    {
        return [

        ];
    }
}