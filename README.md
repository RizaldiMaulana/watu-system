# Sistem Manajemen Kafe

**Sistem Manajemen Kafe** adalah solusi ERP dan POS komprehensif yang dirancang khusus untuk bisnis kafe dan roastery. Sistem ini mengintegrasikan operasional kasir (front-of-house) dengan manajemen inventori dan akuntansi back-office untuk memberikan visibilitas bisnis secara real-time.

## Branch Strategy

| Branch | Fungsi |
|---|---|
| **`release`** | Template bersih siap deploy — tanpa branding spesifik, default environment production |
| **`main`** | Kode terkini yang telah diuji, siap untuk deployment aktif |
| **`test`** | Branch development aktif — tempat semua fitur baru dikerjakan |

> Alur: `test` → PR → `main` → deploy. `release` diperbarui saat ada perubahan infrastruktur mayor.


## Key Features

### Point of Sale (POS)
- **Modern UI**: Fast, responsive interface built with Alpine.js and TailwindCSS.
- **Real-time Calculations**: Automatic calculation of totals, taxes, and change.
- **Receipt Printing**: Integrated thermal receipt printing capability.
- **Payment Methods**: Support for Cash, QRIS, and Debit transactions.
- **Automated Accounting**: Every sale automatically creates Journal Entries (Revenue & COGS).

### Inventory & Procurement
- **Ingredient Management**: Track stock levels of raw materials (beans, milk, sugar, etc.).
- **Recipe Engine**: Link products to ingredients. Selling a "Latte" automatically deducts milk and coffee beans from stock.
- **Smart Purchasing**: Record purchases from suppliers.
- **Weighted Average Cost (WAC)**: System automatically recalculates the Cost Price of ingredients upon every purchase to ensure accurate COGS.

### Accounting & Finance
- **Double-Entry Ledger**: Full adherence to accounting standards (Debits = Credits).
- **Automated Journaling**: Purchases and Sales automatically generate journals.
- **Cost of Goods Sold (HPP)**: Precise tracking of profit per transaction by calculating the exact cost of ingredients used.
- **Chart of Accounts**: Customizable financial accounts (Assets, Liabilities, Equity, Revenue, Expenses).

### Security & Access Control
- **Role-Based Access Control (RBAC)**:
  - **Admin**: Full system access.
  - **Manager**: Access to reports and operational overrides.
  - **Barista**: Restricted to POS and Order management.
  - **Roaster**: Access to production and raw material inventory.
- **Secure Authentication**: Built on Laravel Breeze with robust session management.
- **Data Integrity**: Database transactions ensure that sales, stock updates, and journal entries either all succeed or all fail.

## Technology Stack

- **Framework**: [Laravel 12](https://laravel.com) (PHP 8.2+)
- **Database**: MySQL / MariaDB
- **Frontend**: 
  - [Blade Templates](https://laravel.com/docs/blade) (Server-side rendering)
  - [TailwindCSS](https://tailwindcss.com) (Utility-first styling)
  - [Alpine.js](https://alpinejs.dev) (Lightweight JavaScript for interactivity)
- **Deployment**: Optimized for standard LAMP/LEMP stacks.

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/watu-system.git
   cd watu-system
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database credentials in `.env`*

4. **Database Migration & Seeding**
   ```bash
   php artisan migrate --seed
   ```
   *This will create the database structure and seed default users, roles, and the chart of accounts.*

5. **Run the Application**
   ```bash
   php artisan serve
   ```

## Security Measures

- **CSRF Protection**: All forms are protected against Cross-Site Request Forgery.
- **Input Validation**: Strict server-side validation using Laravel Form Requests.
- **SQL Injection Prevention**: Usage of Eloquent ORM and prepared statements.
- **XSS Protection**: Auto-escaping of output in Blade templates.

---
© 2025 Rizaldi Ilman. All rights reserved.
