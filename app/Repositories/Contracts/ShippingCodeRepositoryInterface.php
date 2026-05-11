<?php

namespace App\Repositories\Contracts;

interface ShippingCodeRepositoryInterface
{
    public function create(array $data);
    public function getAllWithDetails();
    public function findByCode(string $code);
}
