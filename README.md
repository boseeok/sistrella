# 🧶 Crochet Store

A full-featured handmade-crochet e-commerce platform built with **Laravel 12**. It ships with a
customer storefront, a role-based admin panel, a REST API, PDF invoices, and a signature
**prepayment / cash-on-delivery** ordering policy tailored for WhatsApp-driven sales in Nepal.

---

## ✨ Features

### Storefront (customer-facing)
- Home page with hero/promo banners, featured / trending / best-seller / new-arrival / flash-sale carousels and recently-viewed products
- Catalogue with category tree, search, price/stock filters and sorting
- Product detail with image gallery, variants, reviews, related products and a WhatsApp inquiry button
- Database-backed cart (guests + logged-in users), save-for-later, coupons, live AJAX cart badge
- Guest & authenticated checkout with the prepayment policy applied automatically
- Order confirmation, **WhatsApp order hand-off**, payment-proof upload, and order tracking
- Customer account: dashboard, orders, addresses, wishlist, profile & password
- Custom crochet order requests with inspiration-image uploads
- About / Contact / Newsletter / Privacy / Terms pages
- Floating WhatsApp button on every page

### Admin panel (`/admin`)
- Dashboard with KPI widgets and Chart.js graphs
- Orders management with status workflow, history, internal notes & tracking
- **Payment verification queue** (verify / reject customer payment proofs, record offline payments)
- Products (full CRUD, images, flash sales, flags), Categories, Coupons, Banners
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

All passwords are `password`.

| Role | Email | Access |
|------|-------|--------|
| Admin | `admin@crochetstore.test` | Full admin panel |
| Manager | `manager@crochetstore.test` | All except roles/staff |
| Staff | `staff@crochetstore.test` | Orders, payments, inventory, custom requests |
| Customer | `aarati@example.com` (and 4 more `*@example.com`) | Storefront only |

Admin login: **http://127.0.0.1:8000/admin/login**

The seeders also create categories, ~25 products (with flash sales), coupons
(`WELCOME10`, `FLAT200`, `FESTIVE15`), banners, demo orders across every status,
custom requests, reviews and newsletter/contact records.

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

## 📝 Notes
- Demo product/banner images use placeholder URLs automatically (no files needed). Uploading real
  images through the admin stores them under `storage/app/public` and serves them via the symlink.
- Default mailer is `log` — password reset / verification emails are written to
  `storage/logs/laravel.log` in local development.
- Change all seeded passwords before any production use.
