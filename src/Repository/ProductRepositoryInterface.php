<?php

namespace App\Repository;

interface ProductRepositoryInterface
{
    public function findByFilters(array $data): array;
}
