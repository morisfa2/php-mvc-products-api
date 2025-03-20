<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

class Database
{
    public static function setupTestData(): array
    {
        // در اینجا داده‌های تست را برمی‌گردانیم
        return [
            'id' => 'test-123',
            'name' => 'Test Product',
            'price' => 99.99,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Test Brand']
        ];
    }
    
    public static function cleanupTestData(): void
    {
        // پاکسازی داده‌های تست
    }
} 