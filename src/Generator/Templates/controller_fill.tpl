<?php

namespace App\Http\Controllers{namespace};

use App\Http\Requests\{class_name}\StoreRequest;
use App\Http\Requests\{class_name}\UpdateRequest;
use App\Http\Responses\{class_name}\IndexResponse;
use App\Http\Responses\{class_name}\ShowResponse;
use App\Services\{class_name}Service;
use App\Http\Controllers\Controller;

/**
 * Class {class_name}Controller
 * @package App\Http\Controllers{namespace}
 */
class {class_name}Controller extends Controller
{
    protected ${var_name}Service;

    public function __construct({class_name}Service ${var_name}Service)
    {
        $this->{var_name}Service = ${var_name}Service;
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Contracts\Support\Responsable
    */
    public function index()
    {
        $list = $this->{var_name}Service->get{classes_name}();

        return new IndexResponse($list);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        return response_api($this->{var_name}Service->store{class_name}($data));
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Contracts\Support\Responsable
    */
    public function show($id)
    {
        $data = $this->{var_name}Service->get{class_name}($id);

        return new ShowResponse($data);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Foundation\Http\FormRequest  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();
        $bool = $this->{var_name}Service->update{class_name}($id, $data);

        return response_api($bool);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $bool = $this->{var_name}Service->delete{class_name}($id);

        return response_api($bool);
    }
}
