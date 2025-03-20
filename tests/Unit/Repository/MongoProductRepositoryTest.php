<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use App\Repository\MongoProductRepository;
use PHPUnit\Framework\TestCase;

class MongoProductRepositoryTest extends TestCase
{
    private MongoProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new MongoProductRepository();
    }

    public function testCanCreateProduct(): void
    {
        $data = [
            'name' => 'Test Product',
            'price' => 99.99,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Test']
        ];

        $result = $this->repository->create($data);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($data['name'], $result['name']);
    }
} 