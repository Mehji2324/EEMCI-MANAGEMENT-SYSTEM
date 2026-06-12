<?php

namespace App\Repositories\Contracts;

interface StudentRepositoryInterface
{
    public function all(array $filters = []);
    public function find(int $id);
    public function findPending(array $filters = []);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
