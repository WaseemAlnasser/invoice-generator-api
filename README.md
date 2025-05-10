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
- **Database**: MySQL 

> Frontend client (React or Vue) can be found in (To Be Added)

---

## 🧑‍💻 Getting Started

### 🔧 Requirements
- PHP 8.2+
- Composer
- MySQL


### 📦 Installation

```bash
git clone https://github.com/WaseemAlnasser/invoice-generator-api.git
cd invoice-generator-backend

composer install
cp .env.example .env
php artisan key:generate

# Configure your .env DB settings
php artisan migrate
php artisan storage:link

# Start server
php artisan serve
```

---

## 🔐 Authentication

Register/login via:
```http
POST /api/register
POST /api/login
```

All protected routes require a `Bearer {token}` in the `Authorization` header.

---

## 📬 Sending Invoice Emails

Send a PDF invoice to your client:
```http
POST /api/invoices/{id}/send
```

Make sure `MAIL_MAILER` and `MAIL_FROM_ADDRESS` are set in `.env`.

---

## 🖼 Branding API

```http
POST /api/branding
```

- `company_name`: text
- `company_address`: text
- `company_logo`: image file (form-data)

---

## 🌍 Public Invoice Link

```http
GET /public/invoice/{uuid}
```

- No auth required
- Auto-loaded branding and invoice info
- Print and PDF download supported

---

## 🧪 Postman Collection

You can import the full Postman collection from:
📎 [`Invoice_Generator_Postman_Collection.json`](./Invoice_Generator_Postman_Collection.json)

---

## 📜 License

This project is open-sourced under the [MIT License](LICENSE).

---

## 🙌 Contributing

Pull requests are welcome! Feel free to open issues, suggestions, or new ideas for integrations (e.g., Stripe, analytics, etc.)

---

## 💼 Maintainer

**Waseem Alnasser**  
Dubai, UAE  
[Github](https://github.com/WaseemAlnasser/),
[LinkedIn](https://ae.linkedin.com/in/waseem-alnasser-887185251)
[Instagram](https://www.instagram.com/wassem.alnasser/)
