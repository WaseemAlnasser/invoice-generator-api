# Invoice Generator API

A full-featured, open-source Laravel API for generating, emailing, and managing invoices — designed for developers and small businesses. Easily integrates with a Vue or React frontend.

![Laravel](https://img.shields.io/badge/built%20with-Laravel-red?style=flat-square)
![Open Source](https://img.shields.io/badge/license-MIT-green?style=flat-square)

---

## 🚀 Features

- ✅ **Authentication** using Laravel Sanctum
- 👥 **Client Management** (CRUD)
- 🧾 **Invoice Management**
    - Create invoices with multiple items
    - Auto-calculated totals
    - Support for `draft`, `sent`, `paid`, and `overdue` status
- 📄 **PDF Generation** using DomPDF
- 📧 **Send Invoice via Email** with PDF attachment
- 📊 **Dashboard Stats** (paid, unpaid, total amounts)
- 🎨 **Branding Support**: Upload company name, logo, and address
- 🌍 **Public Invoice View** (HTML version sharable via link)
- 📥 **Download PDF** from preview or dashboard

---

## ⚙️ Tech Stack

- **Backend**: Laravel 12
- **PDF Engine**: barryvdh/laravel-dompdf
- **Auth**: Laravel Sanctum
- **Database**: MySQL (or SQLite for dev)

> Frontend client (React or Vue) can be found in [Will be added soon]

---

## 🧑‍💻 Getting Started

### 🔧 Requirements
- PHP 8.2+
- Composer
- MySQL / SQLite
- Node.js (for frontend)

### 📦 Installation

```bash
git clone https://github.com/your-username/invoice-generator-backend.git
cd invoice-generator-backend

composer install
cp .env.example .env
php artisan key:generate

# Configure your .env DB settings
php artisan migrate
php artisan storage:link

# Start server
php artisan serve
