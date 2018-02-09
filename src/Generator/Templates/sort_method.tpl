    public function order($entity, $direction)
    {
        return $entity->orderBy('{var_name}', $this->resolveOrderDirection($direction));
    }