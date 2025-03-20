<?php

declare(strict_types=1);

namespace App\Model\Enum;

enum CategoryEnum: string
{
    case ELECTRONICS = 'electronics';
    case CLOTHING = 'clothing';
    case BOOKS = 'books';
    case FOOD = 'food';
} 