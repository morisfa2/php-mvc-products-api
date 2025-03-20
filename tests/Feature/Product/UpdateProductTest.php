<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use App\DTO\ProductCreateDTO;
use App\Repository\MongoProductRepository;
use App\Service\ProductService;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    private ProductService $service;
    private string $productId;

    protected function setUp(): void
    {
        parent::setUp();
        
        // اینجا مستقیماً سرویس را می‌سازیم به جای استفاده از container
        $repository = new MongoProductRepository();
        $this->service = new ProductService($repository);
        
        // ایجاد یک محصول واقعی برای تست
        $dto = new ProductCreateDTO(
            'Original Product',
            99.99,
            'electronics',
            ['brand' => 'Original Brand']
        );
        
        $product = $this->service->createProduct($dto);
        $this->productId = $product->id;
    }

    public function testCanUpdateProductName(): void
    {
        $result = $this->service->updateProduct($this->productId, [
            'name' => 'Updated Product Name'
        ]);
        
        $this->assertNotNull($result);
        $this->assertEquals('Updated Product Name', $result->name);
    }

    public function testCanUpdateProductPrice(): void
    {
        $result = $this->service->updateProduct($this->productId, [
            'price' => 299.99
        ]);
        
        $this->assertNotNull($result);
        $this->assertEquals(299.99, $result->price);
    }

    public function testUpdatePreservesUnchangedFields(): void
    {
        // اول محصول را دریافت می‌کنیم
        $original = $this->service->getProduct($this->productId);
        
        // فقط نام را آپدیت می‌کنیم
        $result = $this->service->updateProduct($this->productId, [
            'name' => 'Only Name Updated'
        ]);
        
        // چک می‌کنیم که فقط نام تغییر کرده و بقیه فیلدها ثابت مانده‌اند
        $this->assertEquals('Only Name Updated', $result->name);
        $this->assertEquals($original->price, $result->price);
        $this->assertEquals($original->category, $result->category);
        $this->assertEquals($original->attributes, $result->attributes);
    }
} 