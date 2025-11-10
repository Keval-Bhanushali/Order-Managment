# Laravel Order Management System

This is a simple Order Management System built with Laravel. It provides web UI and basic APIs to manage Customers, Products and Orders (with Order Items).

Below are setup instructions, brief developer notes, and how to use the newly added Products CRUD section.

## Prerequisites

- PHP 8.x or later
- Composer
- MySQL (or another supported database)
- Node/npm (optional, for frontend tooling)

## Quick Setup

1. Clone the repository and install dependencies

```powershell
git clone <repository-url>
cd order-management
composer install
```

2. Environment

```powershell
copy .env.example .env
```

Edit `.env` and set your database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

3. Generate app key

```powershell
php artisan key:generate
```

4. Run migrations and seeders

```powershell
php artisan migrate --seed
```

5. Serve the application

```powershell
php artisan serve
```

Open: http://127.0.0.1:8000

## Products CRUD (new)

I added a full Products CRUD section (controller, views, and routes).

- Routes:
  - `GET /products` -> products.index
  - `GET /products/create` -> products.create
  - `POST /products` -> products.store
  - `GET /products/{product}` -> products.show
  - `GET /products/{product}/edit` -> products.edit
  - `PUT /products/{product}` -> products.update
  - `DELETE /products/{product}` -> products.destroy

- Files added/updated:
  - `app/Http/Controllers/ProductController.php` (CRUD logic + validation)
  - `resources/views/products/index.blade.php` (list, pagination, actions)
  - `resources/views/products/create.blade.php` (create form)
  - `resources/views/products/edit.blade.php` (edit form)
  - `resources/views/products/show.blade.php` (details)
  - `resources/views/layouts/app.blade.php` (navigation link)

Usage via UI:
- Visit `/products` to see the product list, create, edit or delete products.

Notes:
- Product fields: `name`, `price` (decimal), and `stock` (integer).
- The product select in the Orders create page reads `price` and `stock` attributes to calculate subtotals and validate quantities client-side.

## Database Structure (summary)

- `customers`: id, name, email, phone, timestamps
- `products`: id, name, price, stock, timestamps
- `orders`: id, customer_id, total_amount, status, timestamps
- `order_items`: id, order_id, product_id, quantity, price, timestamps

## API Endpoints

You have API endpoints under `routes/api.php` (if enabled). The project includes an example API to list orders with customer and items:

```
GET /api/orders
```

Response includes nested customer and order_items with product info.

## Troubleshooting & Helpful Commands

- Clear caches (if routes/config not updated):

```powershell
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

- Re-run migrations (reset and seed):

```powershell
php artisan migrate:fresh --seed
```

- Check Laravel logs for errors:

```
storage/logs/laravel.log
```

## Next steps / Recommendations

- Prevent deleting products which have order items (business rule) — currently deletion is allowed.
- Add product search and inline-edit on the products index for faster management.
- Add role-based access if multiple users manage products/orders.

If you want, I can implement the delete-prevention rule and a small inline-stock editor on the products index next.
