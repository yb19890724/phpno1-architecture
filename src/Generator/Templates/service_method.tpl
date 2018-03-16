   /**
    * Get {classes_name}  and paginate.
    *
    * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
    */
    public function get{classes_name}()
    {
        return $this->{var_name}Repository->paginate();
    }

   /**
    * Get one {class_name}  by primary key.
    *
    * @param $id
    * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
    */
    public function get{class_name}($id)
    {
        return $this->{var_name}Repository->find($id);
    }

   /**
    * Create a new line on {class_name}.
    *
    * @param array $data
    * @return \Illuminate\Database\Eloquent\Model|$this
    */
    public function store{class_name}($data)
    {
        return $this->{var_name}Repository->create($data);
    }

   /**
    * Update {class_name} one line by primary key.
    *
    * @param $id
    * @param array $data
    * @return int
    */
    public function update{class_name}($id, $data)
    {
        return $this->{var_name}Repository->update($id, $data);
    }

   /**
    * Delete {class_name} one line by primary key.
    *
    * @param $id
    * @return mixed
    */
    public function delete{class_name}($id)
    {
        return $this->{var_name}Repository->deleteById($id);
    }
