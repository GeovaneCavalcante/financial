<?php

namespace App\Repositories;

interface IBaseRepository
{

    public function all(): object;

    public function findOne(string $id): ?object;

    public function findByColumn(string $column, $value): object;

    public function save(array $attributes): object;

    public function update(string $id, array $attributes): bool;

    public function delete(string $id): bool;
}
