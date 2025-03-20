<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\DTO\ProductDTO;
use App\DTO\ProductCreateDTO;
use App\Repository\ProductRepositoryInterface;
use App\Service\ProductService;
use PHPUnit\Framework\TestCase;
use Mockery;

class ProductServiceTest extends TestCase
{
    private ProductService $service;
    private ProductRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(ProductRepositoryInterface::class);
        $this->service = new ProductService($this->repository);
    }

    public function testCreateProduct(): void
    {
        $dto = new ProductCreateDTO(
            'Test Product',
            99.99,
            'electronics',
            ['brand' => 'Test Brand']
        );

        $expectedProduct = new ProductDTO(
            'test-id',
            'Test Product',
            99.99,
            'electronics',
            ['brand' => 'Test Brand'],
            new \DateTime()
        );

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->andReturn([
                'id' => 'test-id',
                'name' => 'Test Product',
                'price' => 99.99,
                'category' => 'electronics',
                'attributes' => ['brand' => 'Test Brand']
            ]);

        $result = $this->service->createProduct($dto);

        $this->assertInstanceOf(ProductDTO::class, $result);
        $this->assertEquals('Test Product', $result->name);
        $this->assertEquals(99.99, $result->price);
        $this->assertEquals('electronics', $result->category);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 