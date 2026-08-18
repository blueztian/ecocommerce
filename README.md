# EcoCommerce — Setup & Run Instructions

## Requirements
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- A local web server: XAMPP, WAMP, Laragon, or PHP's built-in server

---

## 1. Configure the Database

Edit `config/database.php` and set your MySQL credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecocommerce');
define('DB_USER', 'root');   // your MySQL username
define('DB_PASS', '');       // your MySQL password
```

---

## 2. Create the Database

Open phpMyAdmin or a MySQL CLI and run:

```sql
SOURCE /path/to/ecocommerce/database/schema.sql;
SOURCE /path/to/ecocommerce/database/seed.sql;
```

Or from a terminal:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql
```

---

## 3. Run the Application

### Option A — Docker (Easiest, Single Command)

1. Make sure **Docker Desktop** is running.
2. Run in the project root:
   ```bash
   docker compose up --build
   ```
3. Open in your browser:
   - **Website**: `http://localhost:8080`
   - **phpMyAdmin**: `http://localhost:8081`

The database schema and seeds are loaded automatically upon first container startup.

---

### Option B — XAMPP / WAMP / Laragon

Place the project folder inside `htdocs/` (XAMPP) or `www/` (WAMP/Laragon), then visit:

```
http://localhost/ecocommerce/
```

### Option B — PHP Built-in Server

```bash
cd ecocommerce
php -S localhost:8000
```

Then visit: `http://localhost:8000`

---

## 4. Run Tests (optional)

With PHP in your PATH:

```bash
php tests/test.php
```

---

## Files Created / Modified

| File | Status |
|---|---|
| `index.php` | NEW — replaces index.html (products from DB) |
| `cart.php` | NEW — session cart page |
| `checkout.php` | NEW — order confirmation page |
| `bootstrap.php` | NEW — shared session/helper bootstrap |
| `config/database.php` | NEW — PDO connection config |
| `includes/header.php` | NEW |
| `includes/navbar.php` | NEW — with cart badge |
| `includes/footer.php` | NEW |
| `includes/product-card.php` | NEW — reusable product card template |
| `includes/cart-item.php` | NEW — reusable cart row template |
| `actions/cart-add.php` | NEW — AJAX POST |
| `actions/cart-update.php` | NEW — AJAX POST |
| `actions/cart-remove.php` | NEW — POST → redirect |
| `actions/cart-clear.php` | NEW — POST → redirect |
| `actions/order-place.php` | NEW — POST → redirect |
| `services/CartService.php` | NEW |
| `services/OrderService.php` | NEW |
| `repositories/ProductRepository.php` | NEW |
| `database/schema.sql` | NEW |
| `database/seed.sql` | NEW |
| `assets/css/cart.css` | NEW — cart/toast/confirmation styles |
| `assets/js/main.js` | NEW — modal + AJAX cart JS |
| `tests/test.php` | NEW — 21 basic tests |
| `index.html` | UNCHANGED — kept as reference |
| `style.css` | UNCHANGED |

---

## Known Issues / Notes

- **DB credentials** must be set in `config/database.php` before use.
- The original `index.html` is kept but superseded by `index.php`. You can delete it once confirmed working.
- No user authentication — cart is session-based only.
- No payment gateway — orders are placed with status `placed`.
- Test file requires PHP in PATH to run from CLI.
