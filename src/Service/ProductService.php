<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\ProductCreateDTO;
use App\DTO\ProductDTO;
use App\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\Uuid;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function createProduct(ProductCreateDTO $dto): ProductDTO
    {
        $data = [
            'name' => $dto->name,
            'price' => $dto->price,
            'category' => $dto->category,
            'attributes' => $dto->attributes,
            'createdAt' => new \DateTime()
        ];

        $product = $this->repository->create($data);

        return new ProductDTO(
            $product['id'],
            $product['name'],
            $product['price'],
            $product['category'],
            $product['attributes'],
            new \DateTime()
        );
    }

    public function getProduct(string $id): ?ProductDTO
    {
        $product = $this->repository->find($id);
        
        if (!$product) {
            return null;
        }

        return new ProductDTO(
            $product['id'],
            $product['name'],
            $product['price'],
            $product['category'],
            $product['attributes'],
            new \DateTime()
        );
    }

    public function updateProduct(string $id, array $data): ?ProductDTO
    {
        $product = $this->repository->update($id, $data);
        
        if (!$product) {
            return null;
        }

        return new ProductDTO(
            $product['id'],
            $product['name'],
            $product['price'],
            $product['category'],
            $product['attributes'],
            new \DateTime()
        );
    }

    public function deleteProduct(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function listProducts(array $filters = []): array
    {
        $products = $this->repository->findAll($filters);
        
        return array_map(function ($product) {
            return new ProductDTO(
                $product['id'],
                $product['name'],
                $product['price'],
                $product['category'],
                $product['attributes'],
                new \DateTime()
            );
        }, $products);
    }
} 