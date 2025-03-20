<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\Enum\CategoryEnum;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ProductCreateDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 255)]
        public readonly string $name,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly float $price,

        #[Assert\NotBlank]
        public readonly string $category,

        #[Assert\NotBlank]
        #[Assert\Type('array')]
        public readonly array $attributes
    ) {}
} 