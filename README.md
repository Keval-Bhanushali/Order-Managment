# Laravel Order Management System

This is a simple Order Management System built with Laravel, featuring customers, products, and orders with order items. The system allows creating customers and orders, listing and updating order statuses, and provides APIs for data retrieval.

## Prerequisites

- PHP >= 8.x
- Composer
- MySQL or supported database
- Laravel installed globally (optional)

## Setup Instructions

### 1. Clone/Download Project

```bash
git clone <repository-url>
cd order-management
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy `.env.example` to `.env` and setup database credentials:

```bash
cp .env.example .env
```

Update `.env`:

```
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations and Seeders

Create tables and populate fake data:

```bash
php artisan migrate --seed
```

### 6. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Database Structure

Tables:

- **customers**: id, name, email, phone, timestamps
- **products**: id, name, price, stock, timestamps
- **orders**: id, customer_id, total_amount, status, timestamps
- **order_items**: id, order_id, product_id, quantity, price, timestamps

## API Endpoints

### Get list of all orders with customers and order details

```
GET http://localhost:8000/api/orders
```

Response example:

```json
[
  {
    "id": 1,
    "customer": { "id": 1, "name": "John Doe", "email": "john@example.com" },
    "order_items": [
      {"product_id": 2, "quantity": 3, "price": "150.00", "product": {"name": "Product A"}}
    ],
    "total_amount": "150.00",
    "status": "pending"
  }
]
```

## Web Routes

- `/customers` - List customers
- `/customers/create` - Add new customer
- `/orders` - List orders
- `/orders/create` - Create new order
- `/orders/{id}/edit` - Update order status

## Usage

- Create customers via the web form in `/customers/create`.
- Add new products by manually adding to the database or use seeders.
- Create orders by selecting a customer and adding one or more products with quantities.
- View orders list and update their statuses.
- Use API endpoint `/api/orders` for external integration.

## Seeders

- `ProductsTableSeeder` - Creates 10 fake products with name, price, and stock.
- `CustomersTableSeeder` - Creates 10 fake customers.
- `OrdersTableSeeder` - Creates 2 orders per customer with random products and updates stock.

## Notes

- Product stock is checked and reduced automatically when orders are placed.
- Order status can be `pending`, `completed`, or `cancelled`.
- Validation ensures customer emails are unique and orders contain at least one item.

## Troubleshooting

- If API returns 404, ensure routes are registered and server running.
- Clear caches if routes or config not reflecting changes:

```bash
php artisan route:clear
php artisan config:clear
```

- Check `storage/logs/laravel.log` for detailed errors.
