<?php

declare(strict_types=1);

namespace Tests\Integration\Database;

use App\Config\Database;
use MongoDB\Client;
use Tests\TestCase;

class MongoConnectionTest extends TestCase
{
    public function testCanConnectToMongoDB(): void
    {
        $client = Database::getClient();
        
        $this->assertInstanceOf(Client::class, $client);
        
        $database = $client->selectDatabase(Database::getDatabase());
        $collections = iterator_to_array($database->listCollections());
        
        $this->assertIsArray($collections);
    }

    public function testCanPerformBasicOperations(): void
    {
        $client = Database::getClient();
        $collection = $client->selectDatabase(Database::getDatabase())->selectCollection('test_collection');
        
        // Insert
        $insertResult = $collection->insertOne(['test' => 'data']);
        $this->assertTrue($insertResult->isAcknowledged());
        
        // Find
        $document = $collection->findOne(['test' => 'data']);
        $this->assertEquals('data', $document->test);
        
        // Cleanup
        $collection->drop();
    }
} 