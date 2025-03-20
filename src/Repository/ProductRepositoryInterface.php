<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Product;

interface ProductRepositoryInterface
{
    /**
     * Create a new product
     */
    public function create(array $data): array;

    /**
     * Find a product by ID
     */
    public function find(string $id): ?array;

    /**
     * Find all products with optional filters
     */
    public function findAll(array $filters = []): array;

    /**
     * Update a product
     */
    public function update(string $id, array $data): ?array;

    /**
     * Delete a product
     */
    public function delete(string $id): bool;
} 