<?php

namespace App\Repositories\Eloquent;

use App\Models\Container;
use App\Repositories\Contracts\ContainerRepositoryInterface;

class ContainerRepository implements ContainerRepositoryInterface
{
    protected $model;

    public function __construct(Container $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function getAllWithDetails()
    {
        return $this->model->with('cases.parts')->get();
    }

    public function findById($id)
    {
        return $this->model->with('cases.parts')->find($id);
    }
}
