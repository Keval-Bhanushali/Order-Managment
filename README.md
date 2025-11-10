# Laravel Order Management System

This repository contains a small Order Management application built with Laravel. It provides a web UI and simple API endpoints to manage Customers, Products, Orders and Order Items. Below you'll find setup steps, exact CRUD routes, controller behaviors, the database schema (from migrations), and seeder behavior.

## Prerequisites

- PHP 8.x or later
- Composer
- MySQL (or another supported database)
- Node/npm (optional)

## Quick Setup

1. Clone and install dependencies

```powershell
git clone <repository-url>
cd order-management
composer install
```

2. Environment

```powershell
copy .env.example .env
```

Update `.env` with database credentials and other settings.

3. Generate application key

```powershell
php artisan key:generate
```

4. Run migrations and seeders

```powershell
php artisan migrate --seed
```

5. Serve the app

```powershell
php artisan serve
```

Open: http://127.0.0.1:8000

## Routes, Controllers & Views (CRUD details)

The app registers the following resources and controller entry points.

Customers
- Routes: `Route::resource('customers', CustomerController::class)->only(['index','create','store','show'])` (see `routes/web.php`).
- Controller: `app/Http/Controllers/CustomerController.php`
  - index: lists customers (`customers.index` view)
  - create: shows create form (`customers.create` view)
  - store: validates and saves customer
    - Validation rules: name (required, string), email (required, email, unique), phone (nullable)
  - show: displays customer details with related orders (`customers.show` view)
  - edit/update/destroy: not implemented (left intentionally blank)

Products
- Routes: `Route::resource('products', ProductController::class)` plus a `DELETE` route used by the index listing (see `routes/web.php`).
- Controller: `app/Http/Controllers/ProductController.php`
  - index: paginated list, `products.index` view
  - create: `products.create` view
  - store: validation and create
    - Validation: name (required|string|max:255), price (required|numeric|min:0), stock (required|integer|min:0)
  - show: `products.show`
  - edit/update: edit form + update
  - destroy: deletes product (note: no blocking check against order_items yet)
- Views (added):
  - `resources/views/products/index.blade.php`
  - `resources/views/products/create.blade.php`
  - `resources/views/products/edit.blade.php`
  - `resources/views/products/show.blade.php`

Orders
- Routes: `Route::resource('orders', OrderController::class)->only(['index','create','store','edit','update','show'])` plus `GET orders/{order}/print` and an API listing.
- Controller: `app/Http/Controllers/OrderController.php`
  - index: shows orders list (`orders.index`)
  - apiIndex: returns JSON list of recent orders with nested customer and order_items (used for `GET /api/orders`)
  - create: loads `customers` and `products` and shows `orders.create` — the order creation UI uses product price and stock for client-side subtotal/stock validation
  - store: validates request, creates order + order_items inside DB transaction, decrements product stock, computes total_amount
    - Validation: customer_id (exists:customers,id), products (array|min:1), products.*.product_id (exists:products,id), products.*.quantity (integer|min:1)
    - If a product lacks sufficient stock, the store transaction throws and rolls back
  - show: shows order and items
  - edit/update: update order status (supports cancelling which restores stock)
  - print: returns an inline HTML representation of the order (used for printing)

Views used by orders exist under `resources/views/orders/` (create, index, show, edit, print). The `orders.create` view includes client-side JS for dynamic product rows, subtotal/total calculations and stock validation.

## Database schema (from migrations)

These are the current migrations (files under `database/migrations/`), and the exact columns they create:

- `2025_11_10_094955_create_customers_table.php`
  - id (bigint, pk)
  - name (string)
  - email (string, unique)
  - phone (string, nullable)
  - timestamps (created_at, updated_at)

- `2025_11_10_095004_create_products_table.php`
  - id (bigint, pk)
  - name (string)
  - price (decimal(10,2))
  - stock (integer, default 0)
  - timestamps

- `2025_11_10_095010_create_orders_table.php`
  - id (bigint, pk)
  - customer_id (foreignId -> customers.id, onDelete cascade)
  - total_amount (decimal(10,2))
  - status (enum: pending, completed, cancelled) default pending
  - timestamps

- `2025_11_10_095017_create_order_items_table.php`
  - id (bigint, pk)
  - order_id (foreignId -> orders.id, onDelete cascade)
  - product_id (foreignId -> products.id, onDelete cascade)
  - quantity (integer)
  - price (decimal(10,2)) — stores the price for the line (product.price * quantity at time of order)
  - timestamps

## Seeders

Seeders are under `database/seeders/` and included by `DatabaseSeeder`:

- `CustomersTableSeeder` — creates 10 fake customers (name, email, phone) using Faker
- `ProductsTableSeeder` — creates 10 fake products with random price and stock
- `OrdersTableSeeder` — for each customer creates 2 orders with 3 random products each; it decrements product stock when creating orders

Run seeders with `php artisan db:seed` or combined with migrations in `php artisan migrate --seed`.

## API

- `GET /api/orders` (implemented in `OrderController@apiIndex`) — returns JSON list of orders with nested customer and order_items including product info and current product stock.

Response example (abridged):

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "customer": {"id": 1, "name": "John Doe", "email":"john@example.com"},
      "order_items": [ {"product_id":2, "quantity":3, "price":"150.00", "product":{"name":"Product A","current_stock":17}} ],
      "total_amount": "150.00",
      "status": "pending",
      "created_at": "2025-11-10T10:00:00Z"
    }
  ]
}
```

## Usage notes

- Creating orders reduces product stock automatically. The order creation page enforces a client-side stock check and server-side validation also prevents over-ordering.
- Cancelling an order restores stock for the items in that order (handled in `OrderController@update`).
- Customers currently can be created and viewed; edit/delete for customers are not implemented yet.

## Helpful artisan commands

- Clear caches (useful when changing routes or env):

```powershell
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

- Reset database and re-seed (use with caution):

```powershell
php artisan migrate:fresh --seed
```

## Recommendations / Next steps

- Prevent deleting products that have order items (business rule) — currently `ProductController@destroy` deletes products outright.
- Add edit/update for customers if you need to modify customer info from the UI.
- Add product search and inline stock editing on the products index for faster inventory management.
- Add authentication/authorization if multiple users will manage orders and products.

If you'd like, I can implement the delete-prevention rule and add an inline stock editor on the products index next. Reply which to prioritize.
