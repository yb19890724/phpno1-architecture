<?php

namespace {namespace};

use Illuminate\Contracts\Support\Responsable;

class {upper_name}{response_type}Response implements Responsable
{
    protected ${name};

    public function __construct(${name})
    {
        $this->{name} = ${name};
    }

    public function toResponse($request)
    {
        ${name} = $this->transform{upper_name}();

        return ${name};
    }

    protected function transform{upper_name}()
    {
        return $this->{name}->map(function ($model) {
            return [

            ];
        });
    }
}