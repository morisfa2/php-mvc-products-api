# 🚀 Project Setup & API Documentation

## 📌 Using MongoDb And php +8.2



## 📌 Getting Started

After cloning the project, build and run the application using Docker:

```sh
docker compose up --build
```

The app will run on **port 8000**, accessible at:

```
http://localhost:8000/products
```

---

## 🧪 Running Tests

You can run tests inside the Docker container. First, enter the container:

```sh
docker-compose exec php bash
```

Or, if you prefer using the container ID (e.g., `19dc5339ee65`), run:

```sh
docker exec -it 19dc5339ee65 bash
```

Then, execute the test suite:

```sh
composer test
```

The tests include **HTTP** and **Feature** tests.

---

## 🔥 API Endpoints

### ➕ Create a Product

```sh
curl -X POST http://localhost:8000/products \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Samsung Smartphone",
    "price": 799.99,
    "category": "electronics",
    "attributes": {
      "brand": "Samsung",
      "model": "Galaxy S21",
      "color": "Black"
    }
  }'
```

### 📋 List All Products

```sh
curl -X GET http://localhost:8000/products
```

### 🔍 Get Product Details

```sh
curl -X GET http://localhost:8000/products/{product_id}
```

### ✏️ Update a Product

```sh
curl -X PATCH http://localhost:8000/products/{product_id} \
  -H "Content-Type: application/json" \
  -d '{
    "price": 749.99,
    "attributes": {
      "color": "Blue",
      "storage": "128GB"
    }
  }'
```

### ❌ Delete a Product

```sh
curl -X DELETE http://localhost:8000/products/{product_id}
```

---

## 🎯 Filtering Products

### Get All Products

```sh
curl -X GET http://localhost:8000/products
```

### Filter by Category

```sh
curl -X GET "http://localhost:8000/products?category=electronics"
```

### Filter by Price Range

```sh
curl -X GET "http://localhost:8000/products?minPrice=500&maxPrice=1000"
```

### Combine Filters

```sh
curl -X GET "http://localhost:8000/products?category=electronics&minPrice=700"
```

---

## 📄 Swagger Documentation

If You Wanna To Use Apis in Swagger You Can Find Alll In This path:
```
Src/Controller/ProductController.php
```

A Sample : 
```php
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
```
