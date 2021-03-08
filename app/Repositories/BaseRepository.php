<?php

namespace App\Repositories;


class BaseRepository implements IBaseRepository
{
    protected $obj;

    protected function __construct(object $obj)
    {
        $this->obj = $obj;
    }

    /**
     * Retorna uma coleção do modelo fornecido
     *
     * @return object
     */
    public function all(): object
    {
        return $this->obj->all();
    }

    /**
     * Retorna um objeto do modelo fornecido ou nulo
     *
     * @param string $id
     *
     * @return object|null
     */
    public function findOne(string $id): ?object
    {
        return $this->obj->find($id);
    }

    /**
     * Retorna um objeto do modelo fornecido com base na coluna e valor fornecido
     *
     * @param string $column
     * @param        $value
     *
     * @return object
     */
    public function findByColumn(string $column, $value): object
    {
        return $this->obj->where($column, $value)->get();
    }

    /**
     *  Persiste um registro do modelo fornecido
     *
     * @param array $attributes
     *
     * @return object
     */
    public function save(array $attributes): object
    {
        $this->obj->fill($attributes);
        $this->obj->save();
        return $this->obj->fresh();
    }

    /**
     *  Atualiza um registro do modelo fornecido
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return bool
     */
    public function update(string $id, array $attributes): bool
    {
        return $this->obj->find($id)->update($attributes);
    }

    /**
     * Delete um registro do modelo fornecido
     *
     * @param string $id
     *
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->obj->find($id)->delete();
    }
}
