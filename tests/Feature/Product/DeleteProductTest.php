<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use App\DTO\ProductCreateDTO;
use App\Repository\MongoProductRepository;
use App\Service\ProductService;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    private ProductService $service;
    private string $productId;

    protected function setUp(): void
    {
        parent::setUp();
        
        $repository = new MongoProductRepository();
        $this->service = new ProductService($repository);
        
        $dto = new ProductCreateDTO(
            'Test Product for Delete',
            99.99,
            'electronics',
            ['brand' => 'Test Brand']
        );
        
        $product = $this->service->createProduct($dto);
        $this->productId = $product->id;
    }

    public function testCanDeleteProduct(): void
    {
        $result = $this->service->deleteProduct($this->productId);
        $this->assertTrue($result);
    }

    public function testDeletedProductIsNotListedInProducts(): void
    {
        // اول محصول را حذف می‌کنیم
        $this->service->deleteProduct($this->productId);
        
        // بعد چک می‌کنیم که در لیست محصولات نباشد
        $products = $this->service->listProducts();
        
        $found = false;
        foreach ($products as $product) {
            if ($product->id === $this->productId) {
                $found = true;
                break;
            }
        }
        
        $this->assertFalse($found);
    }

    public function testCannotDeleteNonExistentProduct(): void
    {
        $result = $this->service->deleteProduct('non-existent-id');
        $this->assertFalse($result);
    }
} 