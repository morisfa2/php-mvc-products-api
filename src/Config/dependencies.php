<?php

declare(strict_types=1);

use App\Controller\ProductController;
use App\Repository\MongoProductRepository;
use App\Repository\ProductRepositoryInterface;
use App\Service\ProductService;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

return [
    ProductRepositoryInterface::class => \DI\autowire(MongoProductRepository::class),

    ProductService::class => \DI\autowire(),

    ProductController::class => \DI\autowire(),

    ValidatorInterface::class => \DI\factory(function() {
        return Validation::createValidator();
    }),
]; 