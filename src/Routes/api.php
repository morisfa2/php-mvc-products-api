<?php

declare(strict_types=1);

use App\Controller\ProductController;

$r->addRoute('GET', '/products', [ProductController::class, 'list']);
$r->addRoute('POST', '/products', [ProductController::class, 'create']);
$r->addRoute('GET', '/products/{id}', [ProductController::class, 'get']);
$r->addRoute('PATCH', '/products/{id}', [ProductController::class, 'update']);
$r->addRoute('DELETE', '/products/{id}', [ProductController::class, 'delete']); 