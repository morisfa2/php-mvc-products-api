<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Tests\Http\Api\Traits\MakesHttpRequests;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use MakesHttpRequests;
    
    private array $testProduct;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupHttpClient();
        $this->testProduct = Database::setupTestData();
    }
    
    private function setupHttpClient(): void
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8000',
            'http_errors' => false
        ]);
    }
    
    public function testCanCreateProduct(): void
    {
        $this->markTestSkipped('HTTP tests need a running server');
        
        $data = [
            'name' => 'New Test Product',
            'price' => 149.99,
            'category' => 'electronics',
            'attributes' => ['brand' => 'New Brand']
        ];
        
        $response = $this->makeRequest('POST', '/products', $data);
        
        $this->assertEquals(201, $response['status']);
        $this->assertArrayHasKey('id', $response['data']);
        $this->assertEquals($data['name'], $response['data']['name']);
    }
    
    public function testCanGetProduct(): void
    {
        $this->markTestSkipped('HTTP tests need a running server');
        
        $response = $this->makeRequest('GET', '/products/' . $this->testProduct['id']);
        
        $this->assertEquals(200, $response['status']);
        $this->assertEquals($this->testProduct['name'], $response['data']['name']);
    }
    
    public function testCanUpdateProduct(): void
    {
        $this->markTestSkipped('HTTP tests need a running server');
        
        $data = ['price' => 199.99];
        
        $response = $this->makeRequest('PATCH', '/products/' . $this->testProduct['id'], $data);
        
        $this->assertEquals(200, $response['status']);
        $this->assertEquals(199.99, $response['data']['price']);
    }
    
    public function testCanDeleteProduct(): void
    {
        $this->markTestSkipped('HTTP tests need a running server');
        
        $response = $this->makeRequest('DELETE', '/products/' . $this->testProduct['id']);
        
        $this->assertEquals(204, $response['status']);
    }
    
    public function testCanListProducts(): void
    {
        $this->markTestSkipped('HTTP tests need a running server');
        
        $response = $this->makeRequest('GET', '/products');
        
        $this->assertEquals(200, $response['status']);
        $this->assertArrayHasKey('data', $response['data']);
    }
    
    protected function tearDown(): void
    {
        Database::cleanupTestData();
        parent::tearDown();
    }
} 