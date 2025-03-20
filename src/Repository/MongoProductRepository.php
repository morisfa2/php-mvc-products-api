<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Product;
use App\Model\Enum\CategoryEnum;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;
use App\Exception\ProductNotFoundException;

class MongoProductRepository implements ProductRepositoryInterface
{
    private Collection $collection;

    public function __construct()
    {
        $client = new Client($_ENV['MONGO_URI'] ?? 'mongodb://mongodb:27017');
        $this->collection = $client->selectDatabase($_ENV['MONGO_DATABASE'] ?? 'products_db')->selectCollection('products');
    }

    public function create(array $data): array
    {
        $insertResult = $this->collection->insertOne([
            'name' => $data['name'],
            'price' => (float) $data['price'],
            'category' => $data['category'],
            'attributes' => $data['attributes'],
            'createdAt' => new \MongoDB\BSON\UTCDateTime()
        ]);

        $id = (string) $insertResult->getInsertedId();
        
        return [
            'id' => $id,
            'name' => $data['name'],
            'price' => (float) $data['price'],
            'category' => $data['category'],
            'attributes' => $data['attributes']
        ];
    }

    public function find(string $id): ?array
    {
        try {
            $document = $this->collection->findOne(['_id' => new ObjectId($id)]);
            
            if (!$document) {
                return null;
            }
            
            return [
                'id' => (string) $document->_id,
                'name' => $document->name,
                'price' => (float) $document->price,
                'category' => $document->category,
                'attributes' => (array) $document->attributes
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findAll(array $filters = []): array
    {
        $query = [];
        
        if (isset($filters['category'])) {
            $query['category'] = $filters['category'];
        }
        
        if (isset($filters['minPrice'])) {
            $query['price']['$gte'] = (float) $filters['minPrice'];
        }
        
        if (isset($filters['maxPrice'])) {
            $query['price']['$lte'] = (float) $filters['maxPrice'];
        }

        $cursor = $this->collection->find($query);
        $products = [];
        
        foreach ($cursor as $document) {
            $products[] = [
                'id' => (string) $document->_id,
                'name' => $document->name,
                'price' => (float) $document->price,
                'category' => $document->category,
                'attributes' => (array) $document->attributes
            ];
        }
        
        return $products;
    }

    public function update(string $id, array $data): ?array
    {
        try {
            $updateData = [];
            
            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }
            
            if (isset($data['price'])) {
                $updateData['price'] = (float) $data['price'];
            }
            
            if (isset($data['category'])) {
                $updateData['category'] = $data['category'];
            }
            
            if (isset($data['attributes'])) {
                $updateData['attributes'] = $data['attributes'];
            }
            
            $result = $this->collection->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $updateData]
            );
            
            if ($result->getModifiedCount() === 0 && $result->getMatchedCount() === 0) {
                return null;
            }
            
            return $this->find($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete(string $id): bool
    {
        try {
            $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findById(string $id): ?Product
    {
        $document = $this->collection->findOne(['_id' => new ObjectId($id)]);
        
        if (!$document) {
            throw new ProductNotFoundException("Product not found with id: $id");
        }

        return $this->documentToProduct($document);
    }

    private function documentToProduct($document): Product
    {
        return new Product(
            (string) $document->_id,
            $document->name,
            (float) $document->price,
            CategoryEnum::from($document->category),
            (array) $document->attributes,
            $document->createdAt
        );
    }
} 