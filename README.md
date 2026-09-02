# 🌿 EcoCommerce

> **Sustainable Solutions for Online Shopping.**  
> A lightweight, blazing-fast, modern PHP & MySQL e-commerce platform dedicated to eco-friendly, zero-waste, and sustainably sourced lifestyle goods.

---

## ✨ Features at a Glance

- 🛍️ **Dynamic Product Catalog & Filtering**  
  Browse sustainably sourced essentials with category filtering (`EcoHome`, `EcoFashion`, `EcoBeauty`, `EcoGourmet`) and an accessible Quick View modal — one shared modal instance, no per-card DOM bloat.
- 🛒 **Full-Featured Session Cart**  
  Add to cart with instant AJAX updates, quantity modification, live order subtotal calculation, free shipping threshold indicators, and toast feedback (loading / success / error states).
- 💚 **Interactive Wishlist**  
  Save your favorite sustainable products to a personalized session wishlist with instant badge counts and one-click cart transfer.
- 🔍 **Real-Time Live Search**  
  Instant typeahead search with live product suggestions, thumbnail previews, price tags, and debounced database querying.
- 📦 **Seamless Order Placement & Tracking**  
  Streamlined checkout workflow with order summary breakdown, unique order reference IDs, status tracking (`placed`, `processing`, `shipped`, `delivered`), and personal purchase history.
- 👤 **Session User Profile**  
  Customer profile management with automated spending summaries, order history tracking, and session data management.
- 📖 **Resource Center & Eco-Blogs**  
  Rich informational content and articles on *Eco-Friendly Tips*, *Sustainable Living*, and *Green Innovations*.
- 🛡️ **Built-in Security**  
  Strong CSRF token protection on state-modifying requests, SQL injection defense with PDO prepared statements, and XSS sanitization across all templates.
- ♿ **Accessible & Keyboard-Navigable**  
  Visible focus states, skip-to-content link, `aria-expanded` dropdowns, focus-trapped modals, 44px minimum touch targets, and `prefers-reduced-motion` support.
- 🧪 **Comprehensive Automated Test Suite**  
  40 automated unit and integration tests covering database queries, cart calculations, orders, wishlist operations, and session isolation.

---

## 🛠️ Technology Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.0+ (Vanilla / Modern Procedural & OOP) |
| **Database** | MySQL 8.0 / MariaDB with PDO driver |
| **Frontend** | Vanilla JavaScript (ES6+), CSS3 with Design Tokens, HTML5 |
| **Containerization** | Docker & Docker Compose |
| **Typography** | Inter (UI), Nunito Sans (body), Bangers (hero display) — Google Fonts |
| **Icons** | Remix Icons, Ionicons, FontAwesome |

---

## 🚀 Quick Start & Installation

### Option 1: Docker (Recommended — Instant Setup)

The fastest and easiest way to run EcoCommerce is using Docker Compose. Everything (PHP web server, MySQL 8.0, and phpMyAdmin) is pre-configured.

1. **Prerequisites:** Make sure [Docker Desktop](https://www.docker.com/products/docker-desktop/) is installed and running.
2. **Start the application:**
   ```bash
   docker compose up --build
   ```
3. **Access the application:**
   - 🌐 **EcoCommerce Storefront:** [http://localhost:8080](http://localhost:8080)
   - 🗄️ **phpMyAdmin:** [http://localhost:8081](http://localhost:8081)

> *Database schema and seed data are automatically initialized on the first container startup.*

---

### Option 2: Local Environment (XAMPP / WAMP / Laragon / Built-in Server)

1. **Clone the repository:**
   ```bash
   git clone https://github.com/blueztian/ecocommerce.git
   cd ecocommerce
   ```

2. **Configure Database Credentials:**  
   Open `config/database.php` and verify/adjust your MySQL credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'ecocommerce');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

3. **Import Database Schema & Seeds:**  
   Execute the SQL files located in `database/` in your MySQL server:
   ```bash
   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS ecocommerce;"
   mysql -u root -p ecocommerce < database/schema.sql
   mysql -u root -p ecocommerce < database/seed.sql
   ```

4. **Serve the Application:**
   - **XAMPP/WAMP/Laragon:** Move the folder into your web root (`htdocs` or `www`) and visit `http://localhost/ecocommerce/`.
   - **PHP Built-in Server:**
     ```bash
     php -S localhost:8000
     ```
     Then open [http://localhost:8000](http://localhost:8000) in your browser.

---

## 🧪 Running Automated Tests

EcoCommerce includes a built-in automated test suite verifying repositories, cart operations, order placement, security, and wishlist persistence.

### Via Docker:
```bash
docker compose exec web php tests/test.php
```

### Via Local CLI:
```bash
php tests/test.php
```

Expected output:
```text
✔ 1. Product retrieval returns array
✔ 2. At least one product in DB
...
✔ 39. Wishlist no longer contains product
✔ 40. Invalid wishlist product prevented

Passed: 40 | Failed: 0
```

---

## 📂 Project Architecture

```text
ecocommerce/
├── actions/                  # Backend endpoints for AJAX & form submissions
│   ├── cart-add.php          # Add item to cart
│   ├── cart-clear.php        # Clear entire cart
│   ├── cart-remove.php       # Remove specific item from cart
│   ├── cart-update.php       # Update item quantity
│   ├── order-place.php       # Process and finalize order
│   ├── profile-update.php    # Update session profile details
│   ├── search-suggest.php    # AJAX live search suggestions
│   ├── wishlist-add.php      # Add item to wishlist
│   └── wishlist-remove.php   # Remove item from wishlist
├── assets/
│   ├── css/
│   │   ├── cart.css          # Cart and checkout component styles
│   │   ├── enhancements.css  # Navbar dropdowns, search UX, animation overrides
│   │   └── batch3.css        # Responsive grids, focus states, touch targets, reduced-motion
│   └── js/
│       ├── animations.js     # Scroll-reveal animation engine
│       ├── main.js           # Core interactivity: nav, Quick View modal, AJAX cart/wishlist
│       └── search.js         # Live search typeahead logic
├── config/
│   ├── database.php          # PDO database connection setup
│   └── pages.php             # Informational pages & blog articles repository
├── database/
│   ├── schema.sql            # Database tables definition (products, orders, items, etc.)
│   └── seed.sql              # Initial eco-friendly product catalog seed
├── images/                   # Product imagery, category banners, hero, and brand logos
├── includes/                 # Reusable layout partials
│   ├── cart-item.php         # Cart row component
│   ├── footer.php            # Global footer with Ionicons + scripts
│   ├── header.php            # HTML head: meta, fonts, CSS links, skip-to-content link
│   ├── navbar.php            # Navigation header with dropdowns, search, and live badges
│   └── product-card.php      # Product card with data-* attributes for global Quick View
├── repositories/             # Database abstraction layer
│   ├── OrderRepository.php   # Order persistence and history queries
│   └── ProductRepository.php # Product catalog and search queries
├── services/                 # Core business logic
│   ├── CartService.php       # Session-based cart calculations and mutations
│   ├── OrderService.php      # Order validation and creation pipeline
│   └── WishlistService.php   # Session-based wishlist management
├── tests/
│   └── test.php              # Automated test runner (40 assertions)
├── bootstrap.php             # Global bootstrap (sessions, CSRF, autoloader, helpers)
├── cart.php                  # Shopping cart view
├── checkout.php              # Order summary and confirmation view
├── docker-compose.yml        # Docker composition configuration
├── Dockerfile                # PHP Apache image specification
├── index.php                 # Storefront homepage and filtered catalog
├── MODERNIZATION.md          # UI modernization technical notes (Batches 1–4)
├── order.php                 # Order tracking and receipt details view
├── page.php                  # Dynamic routing for informational and blog pages
├── profile.php               # Customer profile and order history dashboard
├── search.php                # Full-text product search results view
├── style.css                 # Global design tokens + component styles (Batches 1–4)
└── wishlist.php              # Saved items wishlist view
```

---

## 🎨 Design System

EcoCommerce uses a CSS custom-property token system defined in `style.css` `:root`. Key tokens:

| Category | Key Tokens |
|---|---|
| **Brand** | `--eco-primary` (forest green) · `--eco-accent` (warm amber) |
| **Surfaces** | `--surface-primary` · `--surface-secondary` · `--surface-warm` |
| **Spacing** | `--space-1` (8px) through `--space-8` (80px) — 8px grid |
| **Radius** | `--radius-sm` (4px) · `--radius-md` (12px) · `--radius-lg` (20px) · `--radius-pill` (9999px) |
| **Shadows** | `--shadow-sm` · `--shadow-md` · `--shadow-lg` |
| **Motion** | `--transition-fast` (150ms) · `--transition-normal` (250ms) · `--transition-slow` (400ms) |
| **Typography** | `--ff-inter` (UI) · `--ff-nunito_sans` (body) · `--ff-display` (hero) |

All legacy variable names (`--olive-classic`, `--amber`, `--platinum`, etc.) are retained as aliases so existing partials continue to work without changes.

See [`MODERNIZATION.md`](MODERNIZATION.md) for the full design token migration table, responsive breakpoint matrix, Quick View architecture change, and accessibility improvements.

---

## 📱 Responsive Breakpoints

| Breakpoint | Categories | Products | Services | Testimonials | Brands |
|---|---|---|---|---|---|
| ≥ 1440px | 4 cols | 5 cols | 4 cols | 3 cols | 5 cols |
| ≥ 1280px | 4 cols | 4 cols | 4 cols | 3 cols | 5 cols |
| ≥ 1024px | 2 cols | 3 cols | 4 cols | 2 cols | 3 cols |
| ≥ 768px | 2 cols | 2 cols | 2 cols | 2 cols | 3 cols |
| < 480px | 2 cols | 2 cols | 1 col | 1 col | 2 cols |

---

## 🔒 Security & Engineering Standards

- **Cross-Site Request Forgery (CSRF):** Unique per-session cryptographic tokens validated across all POST requests.
- **SQL Injection Prevention:** 100% parameterized queries using PDO prepared statements across all repositories.
- **Cross-Site Scripting (XSS):** Context-aware output encoding via the centralized `e()` helper function.
- **Session Isolation:** Orders and customer profiles are bound strictly to active authenticated sessions.
- **Semantic & Accessible Markup:** Built adhering to modern accessibility guidelines — `aria-*` labels, keyboard-navigable dropdowns and modals, focus trapping, skip-to-content link, `prefers-reduced-motion` support, and 44px minimum touch targets on all interactive elements.

---

## 🌿 License

Distributed under the MIT License. See `LICENSE` for more information.
