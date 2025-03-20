# 🚀 Project Setup & API Documentation

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
      "model": "Galaxy S21
