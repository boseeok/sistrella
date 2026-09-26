# 🧶 Sistrella — Handmade Gifts & Boutique

A full-featured handmade-gift e-commerce platform built with **Laravel 12**, selling crochet
pieces, satin ribbon bouquets and fuzzy wire flowers. It ships with a customer storefront, a
role-based admin panel, a REST API, PDF invoices, and a signature **prepayment /
cash-on-delivery** ordering policy tailored for WhatsApp-driven sales in Nepal.

---

## ✨ Features

### Storefront (customer-facing)
- Home page with hero banners, product-line tiles, **shop by occasion**, curated collections, new-arrival / flash-sale / best-seller / trending rails, reviews and recently-viewed products
- **Product lines** as top-level categories (Crochet, Ribbon Bouquets, Fuzzy Wire) with sub-categories of any depth — add jewellery, candles or gift boxes from the admin without code changes
- **Occasions & curated collections** (Birthday, Anniversary, Valentine's Day, Graduation…) with their own pages and a shop filter
- Catalogue with multi-word search, category / occasion / collection / price / stock filters and sorting
- Product detail with image gallery and zoom, **colour swatches & size options** (live price and per-option stock), buy-now, reviews, related products and a WhatsApp inquiry button
- Database-backed cart (guests + logged-in users), save-for-later, coupons, live AJAX cart badge
- Guest & authenticated checkout with the prepayment policy applied automatically
- Order confirmation, **WhatsApp order hand-off**, payment-proof upload, and order tracking
- Customer account: dashboard, orders, addresses, wishlist, profile & password
- Custom / personalised gift requests (product line, occasion, budget, inspiration images)
- SEO: per-page titles and meta, canonical URLs, Open Graph, Product & breadcrumb structured data, `/sitemap.xml`
- About / Contact / Newsletter / Privacy / Terms pages
- Floating WhatsApp button on every page

### Admin panel (`/admin`)
- Dashboard with KPI widgets, Chart.js graphs and revenue / stock per **product line**
- Orders management with status workflow, history, internal notes & tracking
- **Payment verification queue** (verify / reject customer payment proofs, record offline payments)
- Products (full CRUD, gallery with primary image, **colour/size variants**, occasion tags, flash sales, flags), nested Categories, Coupons, Banners
- **Occasions & Collections** manager and a **Page Content** editor (home section + About page) with a sanitized rich-text editor
- Inventory management with low/out-of-stock filters
- Customers (view, activate/deactivate, lifetime value)
- Custom requests (quote, status, convert to order)
- Reports (sales, inventory, customers) with CSV export
- Marketing (newsletter subscribers, contact messages)
- Settings (store identity, prepayment policy, payment details, social links)
- Roles & permissions (RBAC) and staff management

### Platform
- Lightweight, dependency-free **RBAC** (roles → permissions → users)
- REST API (`/api/v1`) secured with **Laravel Sanctum**
- **PDF invoices** via dompdf
- Cached, admin-editable settings (`setting()` helper)

---

## 🧰 Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12 (PHP 8.2+) |
| Database | MySQL |
| Auth | Session (web) + Sanctum tokens (API) |
| UI | Blade + Bootstrap 5 + Bootstrap Icons (CDN) |
| Charts | Chart.js (CDN) |
| PDF | barryvdh/laravel-dompdf |

> The storefront/admin styling is delivered via Bootstrap 5 from a CDN, so the app renders
> correctly **without** running a frontend build. (The Tailwind/Vite scaffold remains available
> if you prefer to compile assets.)

---

## 🚀 Getting Started

### 1. Requirements
- PHP 8.2+
- Composer
- MySQL (a database named `crochet_store` by default)

### 2. Install
```bash
composer install
cp .env.example .env          # if .env does not exist
php artisan key:generate
```

### 3. Configure the database
Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crochet_store
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrate, seed & link storage
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 5. Run
```bash
php artisan serve
```
Visit **http://127.0.0.1:8000**.

---

## 🔑 Seeded Accounts

Locally all passwords are `password`. Staff accounts use `SEED_ADMIN_PASSWORD` when it is set
(in production a random password is generated and printed once if it is not).

| Role | Email | Access |
|------|-------|--------|
| Admin | `admin@crochetstore.test` | Full admin panel |
| Manager | `manager@crochetstore.test` | All except roles/staff |
| Staff | `staff@crochetstore.test` | Orders, payments, inventory, custom requests |
| Customer | `aarati@example.com` (and 4 more `*@example.com`) | Storefront only |

Admin login: **http://127.0.0.1:8000/admin/login**

The seeders also create three product lines with sub-categories, ~40 products (flash sales and
colour/size variants included), occasions and curated collections, coupons (`WELCOME10`,
`FLAT200`, `FESTIVE15`), banners, demo orders across every status, custom requests, reviews and
newsletter/contact records. Demo photos are free-licence images (Unsplash, Pexels, Pixabay)
downloaded into `storage/app/public` on first seed.

---

## 💰 The Prepayment Policy (core business rule)

Configured in **Admin → Settings** (defaults: threshold **NPR 500**, advance **50%**):

- **Order total ≤ threshold** → eligible for **full Cash on Delivery**.
- **Order total > threshold** → a mandatory **advance** (a % of the total) is required to
  confirm the order; the remainder is collected as **COD on delivery**.

The breakdown is computed once in `App\Services\PrepaymentService` and shown consistently on the
product page, cart, checkout, order summary, WhatsApp message, PDF invoice and admin panel.
Prepayment orders are routed to a **WhatsApp hand-off** where the customer arranges the advance;
the customer can also upload payment proof, which lands in the admin **verification queue**.

---

## 🗂️ Project Structure

```
app/
├── Http/Controllers/
│   ├── Admin/      # 15 admin controllers (dashboard, orders, payments, products, …)
│   ├── Api/        # REST API (auth, products, cart)
│   ├── Auth/       # login, register, password reset, email verification, admin auth
│   └── Shop/       # storefront (home, products, cart, checkout, orders, account, …)
├── Models/         # Eloquent models + lightweight RBAC (Concerns/HasRoles)
├── Repositories/   # Product / Category / Order data access
├── Services/       # CartService, OrderService, PaymentService, PrepaymentService,
│                   # CouponService, CustomRequestService, InvoiceService, WhatsAppService,
│                   # DashboardService, SettingService, ActivityLogger
└── Support/        # global helpers: setting(), money(), prepayment_*()

resources/views/
├── layouts/        # app (storefront), admin (sidebar), guest (auth)
├── partials/       # theme, flash, product-card, account-nav, cart-script, …
├── auth/           # login, register, forgot/reset password, verify email
├── shop/           # home, products, cart, checkout, orders, account, wishlist, custom, pages
├── admin/          # dashboard + every admin section's views
└── invoices/       # PDF invoice template

database/
├── migrations/     # full schema (RBAC, catalogue, orders, payments, marketing, …)
└── seeders/        # RolePermission, Admin, Setting, Category, Product, DemoData
```

---

## 🌐 Key Routes

| Area | Example |
|------|---------|
| Storefront | `/`, `/shop`, `/product/{slug}`, `/cart`, `/checkout`, `/orders/track` |
| Account | `/account`, `/account/orders`, `/wishlist`, `/custom-order` |
| Auth | `/login`, `/register`, `/forgot-password` |
| Admin | `/admin`, `/admin/orders`, `/admin/payments/queue`, `/admin/products`, `/admin/settings` |
| API | `/api/v1/products`, `/api/v1/categories`, `/api/v1/login`, `/api/v1/cart` |

Run `php artisan route:list` for the full list.

---

## 🔌 REST API (`/api/v1`)

Token auth via Sanctum. Obtain a token, then send `Authorization: Bearer <token>`.

```bash
# Login → returns { user, token }
curl -X POST http://127.0.0.1:8000/api/v1/login \
  -H "Accept: application/json" \
  -d "email=aarati@example.com&password=password"

# Public catalogue
curl http://127.0.0.1:8000/api/v1/products
curl http://127.0.0.1:8000/api/v1/categories

# Authenticated cart
curl http://127.0.0.1:8000/api/v1/cart -H "Authorization: Bearer <token>"
```

| Method | Endpoint | Auth |
|--------|----------|------|
| POST | `/api/v1/register` · `/api/v1/login` | public |
| GET | `/api/v1/products` · `/api/v1/products/{slug}` · `/api/v1/categories` | public |
| GET | `/api/v1/me` · POST `/api/v1/logout` | token |
| GET | `/api/v1/cart` · POST `/api/v1/cart/add` · DELETE `/api/v1/cart/items/{item}` | token |

---

## 🛡️ RBAC

- **Roles**: `admin` (super-user), `manager`, `staff`, `customer`.
- **Permissions** are grouped (Orders, Payments, Catalogue, …) and attached to roles.
- Route protection: `->middleware('admin')` gates the panel; `->middleware('permission:orders.view')`
  enforces granular access. The `admin` role bypasses granular checks.

---

## 🧾 PDF Invoices

`App\Services\InvoiceService` renders `resources/views/invoices/order.blade.php` with dompdf.
- Customer: **My Orders → Invoice** (`/orders/{number}/invoice`, downloads)
- Admin: **Order → Invoice** (`/admin/orders/{number}/invoice`, streams in browser)

---

## 📦 Useful Commands

```bash
php artisan migrate:fresh --seed   # rebuild + reseed the database
php artisan storage:link           # expose uploaded images
php artisan route:list             # list all routes
php artisan view:clear             # clear compiled Blade cache
php artisan config:clear           # clear settings/config cache
```

---

## ✅ Testing

```bash
php vendor/bin/phpunit
```
Feature tests cover the storefront (home, catalogue filters, search, product page, cart, buy now,
guest checkout, auth, sitemap, API), the admin catalogue (access control, product/image/category/
banner CRUD, page content) and the boutique features (product lines, occasions, variants and the
cart's variant checks, custom gift requests). They run on in-memory SQLite with image downloads faked.

---

## ☁️ Deploying to Railway

The repo is ready for [Railway](https://railway.com) (Railpack builder, FrankenPHP, `public/` as web root):
`railway.json` sets the builder and a `/up` health check, and `start-container.sh` runs migrations,
seeds the demo store **on first deploy only** (`php artisan store:seed-if-empty`), links storage and caches config.

1. New project → **Deploy from GitHub repo** → this repository. Add a **MySQL** database.
2. On the app service add a **volume** mounted at `/app/storage/app/public` (uploads and demo photos).
3. Set variables on the app service:
   ```env
   APP_NAME=Sistrella
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=              # output of: php artisan key:generate --show
   APP_URL=https://your-domain
   DB_CONNECTION=mysql
   DB_URL=${{MySQL.MYSQL_URL}}
   SESSION_SECURE_COOKIE=true
   LOG_CHANNEL=stderr
   MAIL_MAILER=log
   SEED_ADMIN_PASSWORD=  # choose a strong password for the staff accounts
   ```
4. **Networking** → Generate Domain (or add a custom domain and point a CNAME at the target Railway shows).

---

## 📝 Notes
- Uploaded images are stored under `storage/app/public` and served via the `public/storage` symlink.
- Default mailer is `log` — password reset / verification emails are written to
  `storage/logs/laravel.log` in local development.
- Change all seeded passwords before any production use.
