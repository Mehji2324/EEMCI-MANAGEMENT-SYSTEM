<?php

namespace App\Repositories\Contracts;

interface AbsenceRepositoryInterface
{
    public function all(array $filters = []);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
}
