<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\ProductCreateDTO;
use App\Service\ProductService;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController
{
    public function __construct(
        private ProductService $service,
        private ValidatorInterface $validator
    ) {}

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Create a new product",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductCreateDTO")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductDTO")
     *     )
     * )
     */
    public function create(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $dto = new ProductCreateDTO(
            $data['name'] ?? '',
            (float) ($data['price'] ?? 0),
            $data['category'] ?? '',
            $data['attributes'] ?? []
        );
        
        $product = $this->service->createProduct($dto);
        
        header('Content-Type: application/json');
        http_response_code(201);
        echo json_encode($product);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Get a product by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(ref="#/components/schemas/ProductDTO")
     *     )
     * )
     */
    public function get(string $id): void
    {
        $product = $this->service->getProduct($id);
        
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }
        
        header('Content-Type: application/json');
        echo json_encode($product);
    }

    /**
     * @OA\Patch(
     *     path="/products/{id}",
     *     summary="Update a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="category", type="string"),
     *             @OA\Property(property="attributes", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductDTO")
     *     )
     * )
     */
    public function update(string $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $product = $this->service->updateProduct($id, $data);
        
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }
        
        header('Content-Type: application/json');
        echo json_encode($product);
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Delete a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     )
     * )
     */
    public function delete(string $id): void
    {
        $success = $this->service->deleteProduct($id);
        
        if (!$success) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            return;
        }
        
        http_response_code(204);
    }

    /**
     * @OA\Get(
     *     path="/products",
     *     summary="List all products",
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="minPrice",
     *         in="query",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="maxPrice",
     *         in="query",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductDTO")
     *         )
     *     )
     * )
     */
    public function list(): void
    {
        $products = $this->service->listProducts();
        
        header('Content-Type: application/json');
        echo json_encode([
            'data' => $products,
            'count' => count($products)
        ]);
    }
} 