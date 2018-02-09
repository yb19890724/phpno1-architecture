    public function get{class_name}List()
    {
        return $this->{var_name}Repository->paginate();
    }

    public function get{class_name}Detail($id)
    {
        return $this->{var_name}Repository->find($id);
    }

    public function store{class_name}($data)
    {
        return $this->{var_name}Repository->create($data);
    }

    public function update{class_name}($id, $data)
    {
        return $this->{var_name}Repository->update($id, $data);
    }

    public function delete{class_name}($id)
    {
        return $this->{var_name}Repository->deleteById($id);
    }
