<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use App\DTO\ProductCreateDTO;
use App\Repository\MongoProductRepository;
use App\Repository\ProductRepositoryInterface;
use App\Service\ProductService;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    private ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $repository = new MongoProductRepository();
        $this->service = new ProductService($repository);
    }

    public function testCanCreateProductWithValidData(): void
    {
        $dto = new ProductCreateDTO(
            'Test Product',
            99.99,
            'electronics',
            ['brand' => 'Test Brand']
        );

        $product = $this->service->createProduct($dto);

        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(99.99, $product->price);
        $this->assertEquals('electronics', $product->category);
    }

    public function testCreatedProductIsPersistedInDatabase(): void
    {
        $dto = new ProductCreateDTO(
            'Test Product',
            99.99,
            'electronics',
            ['brand' => 'Test Brand']
        );

        $product = $this->service->createProduct($dto);
        
        $foundProduct = $this->service->getProduct($product->id);
        
        $this->assertEquals($product->id, $foundProduct->id);
        $this->assertEquals($product->name, $foundProduct->name);
    }

    public function testCreatedProductTriggersEvents(): void
    {
        // Implement when you have events system
        $this->markTestSkipped('Events system not implemented yet');
    }
} 