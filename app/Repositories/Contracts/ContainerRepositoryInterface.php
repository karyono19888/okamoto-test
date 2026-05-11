<?php

namespace App\Repositories\Contracts;

interface ContainerRepositoryInterface
{
    public function create(array $data);
    public function getAllWithDetails();
    public function findById($id);
}
