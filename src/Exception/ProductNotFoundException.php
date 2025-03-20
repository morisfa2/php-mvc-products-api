<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class ProductNotFoundException extends Exception
{
    public function __construct(string $message = "Product not found")
    {
        parent::__construct($message, 404);
    }
} 