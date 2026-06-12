<?php

namespace App\Repositories\Contracts;

interface NoteRepositoryInterface
{
    public function all(array $filters = []);
    public function find(int $id);
    public function updateOrCreate(array $attributes, array $values);
    public function update(int $id, array $data);
}
