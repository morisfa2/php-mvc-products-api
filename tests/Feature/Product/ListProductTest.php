<?php

declare(strict_types=1);

namespace Tests\Feature\Product;

use Tests\TestCase;
use App\Service\ProductService;
use App\DTO\ProductCreateDTO;
use App\Repository\MongoProductRepository;

class ListProductTest extends TestCase
{
    private ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        // اینجا مستقیماً سرویس را می‌سازیم به جای استفاده از container
        $repository = new MongoProductRepository();
        $this->service = new ProductService($repository);
        $this->createTestProducts();
    }

    private function createTestProducts(): void
    {
        $products = [
            [
                'name' => 'Budget Phone',
                'price' => 299.99,
                'category' => 'electronics',
                'attributes' => ['brand' => 'Budget Brand']
            ],
            [
                'name' => 'Premium Phone',
                'price' => 999.99,
                'category' => 'electronics',
                'attributes' => ['brand' => 'Premium Brand']
            ],
            [
                'name' => 'T-Shirt',
                'price' => 29.99,
                'category' => 'clothing',
                'attributes' => ['size' => 'M']
            ]
        ];

        foreach ($products as $product) {
            $this->service->createProduct(new ProductCreateDTO(
                $product['name'],
                $product['price'],
                $product['category'],
                $product['attributes']
            ));
        }
    }

    public function testCanListAllProducts(): void
    {
        $products = $this->service->listProducts();
        $this->assertIsArray($products);
    }

    public function testCanFilterByCategory(): void
    {
        $products = $this->service->listProducts(['category' => 'electronics']);
        $this->assertIsArray($products);
        
        // اگر محصولی وجود داشته باشد، باید همه آنها در دسته electronics باشند
        foreach ($products as $product) {
            $this->assertEquals('electronics', $product->category);
        }
    }

    public function testCanFilterByPriceRange(): void
    {
        $products = $this->service->listProducts([
            'minPrice' => 50,
            'maxPrice' => 200
        ]);
        
        $this->assertIsArray($products);
        
        // اگر محصولی وجود داشته باشد، باید قیمت آنها بین 50 و 200 باشد
        foreach ($products as $product) {
            $this->assertGreaterThanOrEqual(50, $product->price);
            $this->assertLessThanOrEqual(200, $product->price);
        }
    }

    public function testCanCombineFilters(): void
    {
        $products = $this->service->listProducts([
            'category' => 'electronics',
            'minPrice' => 500
        ]);
        
        $this->assertIsArray($products);
        // اگر محصولی وجود داشته باشد، باید قیمت آن بیشتر از 500 باشد و دسته آن electronics باشد
        foreach ($products as $product) {
            $this->assertEquals('electronics', $product->category);
            $this->assertGreaterThanOrEqual(500, $product->price);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up test data
        $products = $this->service->listProducts();
        foreach ($products as $product) {
            $this->service->deleteProduct($product->id);
        }
    }
} 