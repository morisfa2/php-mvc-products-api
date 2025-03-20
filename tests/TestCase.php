<?php

declare(strict_types=1);

namespace Tests;

use App\Repository\MongoProductRepository;
use App\Repository\ProductRepositoryInterface;
use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestCase extends BaseTestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            ProductRepositoryInterface::class => \DI\autowire(MongoProductRepository::class),
            ValidatorInterface::class => \DI\factory(function() {
                return Validation::createValidator();
            }),
        ]);
        
        $this->container = $containerBuilder->build();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Cleanup after each test if needed
    }
} 