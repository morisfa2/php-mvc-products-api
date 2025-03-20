<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Enum\CategoryEnum;
use MongoDB\BSON\UTCDateTime;

class Product
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly float $price,
        public readonly CategoryEnum $category,
        public readonly array $attributes,
        public readonly UTCDateTime $createdAt
    ) {}
} 