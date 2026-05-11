<?php

namespace App\Repositories\Eloquent;

use App\Models\ShippingCode;
use App\Repositories\Contracts\ShippingCodeRepositoryInterface;

class ShippingCodeRepository implements ShippingCodeRepositoryInterface
{
    protected $model;

    public function __construct(ShippingCode $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function getAllWithDetails()
    {
        return $this->model->with('containers.cases.parts')->get();
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }
}
