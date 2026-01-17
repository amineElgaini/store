# Laravel E‑Commerce Project

A Laravel-based e‑commerce application with an **admin dashboard**, **product & package management**, **shopping cart**, and **order system**. The frontend uses **Blade**, **Tailwind CSS**, and **Alpine.js** for lightweight interactivity without a full SPA.

---

## ✨ Features

### Public / Customer Side

* Home page
* Product listing and product details
* Product variants support
* Shopping cart (add / remove products)
* Place orders as a customer

### Admin Dashboard

* Secure admin area (`auth` + `isAdmin` middleware)
* Dashboard overview
* Category management
* Product management
* Product variant management
* Package management
* Order management (view, update, delete)

### Tech Stack

* **Backend:** Laravel
* **Frontend:** Blade, Tailwind CSS, Alpine.js
* **Database:** MySQL
* **Authentication:** Sunctum

---

## 📁 Project Structure (Important Parts)

```
app/
 └── Http/Controllers/
     ├── Admin/
     │   ├── DashboardController.php
     │   ├── CategoryController.php
     │   ├── ProductController.php
     │   ├── ProductVariantController.php
     │   └── PackageController.php
     ├── CartController.php
     ├── OrderController.php
     └── ProductController.php

resources/views/
 ├── admin/
 ├── products/
 ├── cart/
 └── layouts/
```

---

## 🚀 Installation & Setup

### 1. Clone the repository

```bash
git clone https://github.com/amineElgaini/store.git
cd store
```

### 2. Install dependencies

```bash
composer install
npm install
npm run build
```

### 3. Environment configuration

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials.

### 4. Run migrations

```bash
php artisan migrate --seed
```

### 5. Start the server

```bash
php artisan serve
```

---

## 🔐 Authentication & Admin Access

* Authentication routes are provided via `auth.php`
* Admin routes are protected using:

  * `auth` middleware
  * custom `isAdmin` middleware

```php
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // admin routes
});
```

---

## 🛣️ Routes Overview

### Public Routes

| Method | URI                            | Description              |
| ------ | ------------------------------ | ------------------------ |
| GET    | /                              | Home page                |
| GET    | /products                      | Product list             |
| GET    | /products/{product}            | Product details          |
| GET    | /cart                          | View cart                |
| POST   | /cart/add-product/{variant}    | Add product to cart      |
| DELETE | /cart/remove-product/{variant} | Remove product from cart |
| POST   | /orders                        | Place order              |

### Admin Routes

| Method   | URI                                     | Description       |
| -------- | --------------------------------------- | ----------------- |
| GET      | /admin/dashboard                        | Admin dashboard   |
| Resource | /admin/categories                       | Manage categories |
| Resource | /admin/products                         | Manage products   |
| GET      | /admin/products/{product}/variants/edit | Edit variants     |
| POST     | /admin/products/{product}/variants      | Store variants    |
| PUT      | /admin/variants/{variant}               | Update variant    |
| DELETE   | /admin/variants/{variant}               | Delete variant    |
| Resource | /admin/packages                         | Manage packages   |
| Resource | /admin/orders                           | Manage orders     |

---

## ⚡ Alpine.js Usage

Alpine.js is used to add interactivity without React or Vue.

Examples of usage:

* Variant selection on product pages
* Dynamic cart updates
* Toggle modals and dropdowns in admin UI

Example:

```html
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>

    <div x-show="open" class="mt-2">
        Content here
    </div>
</div>
```

---

## 🛒 Cart & Orders

* Cart data is stored in the **session**
* Supports product variants
* Orders are created from session data
* Admin can manage and update order status
