# Money Tracker

Money Tracker adalah aplikasi pencatatan keuangan personal berbasis Laravel 12 untuk mencatat pemasukan, pengeluaran, kategori pengeluaran, must saving, dan reminder harian. Project ini juga punya mode admin untuk melihat seluruh member dan readonly impersonation.

## Stack

- PHP 8.2
- Laravel 12
- Blade
- jQuery
- Tailwind CSS
- MySQL atau SQLite

## Fitur Utama

- Dashboard member dengan ringkasan `income`, `expense`, `net`, dan `must saving`
- Cash flow ledger dengan filter bulan, tipe, kategori, dan search
- Manajemen kategori beserta persentase must saving
- Reminder WhatsApp harian untuk member `gold`
- Landing page public
- Dark mode dan light mode
- Admin dashboard untuk overview semua member
- Readonly impersonation untuk superadmin

## Konsep Bisnis

Role di aplikasi:

- `superadmin`
- `member`

Tipe member:

- `silver`
- `gold`

Aturan utama:

- Semua data transaksi, kategori, dan reminder milik user masing-masing
- Member `gold` bisa mengaktifkan reminder
- Superadmin bisa melihat data member lewat mode readonly impersonation
- Perhitungan periode bisa memakai mode cutoff `26-25` atau kalender biasa, tergantung setting user

## Modul

### 1. Dashboard

Menampilkan:

- periode aktif
- total income
- total expense
- net cash
- must saving
- spending by category
- transaksi terbaru

### 2. Cash Flow

Menampilkan:

- form input transaksi
- edit transaksi
- hapus transaksi
- ledger transaksi
- filter periode, tipe, kategori, dan keyword

### 3. Category

Menampilkan:

- daftar kategori
- persentase must saving per kategori
- jumlah transaksi per kategori
- form create dan edit kategori

### 4. Reminder

Menampilkan:

- nomor WhatsApp
- jam kirim
- status aktif atau nonaktif
- template pesan
- log reminder terakhir

### 5. Admin

Menampilkan:

- dashboard agregat semua member
- daftar member
- daftar transaksi global
- report
- audit log

## UI Direction

UI project ini memakai arah `neo-brutalism`, bukan dashboard SaaS generik.

Ciri utamanya:

- border tebal
- hard shadow
- typography tegas
- blok warna kontras
- layout editorial
- light dan dark mode

Dokumentasi UI:

- [docs/UI_OVERVIEW.md](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/docs/UI_OVERVIEW.md)
- [docs/UI_PAGES.md](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/docs/UI_PAGES.md)
- [MONEY_TRACKER_BRUTALISM_SPEC.md](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/MONEY_TRACKER_BRUTALISM_SPEC.md)

## Route Penting

- `/` landing page
- `/dashboard` dashboard member
- `/cash-flows` ledger transaksi
- `/categories` kategori
- `/reminder` reminder harian
- `/admin/dashboard` dashboard admin

## Setup Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Jalankan saat development:

```bash
composer run dev
```

## Testing

```bash
composer test
```

## Struktur Singkat

- `app/Http/Controllers` logic halaman dan admin
- `app/Support/MoneyTrackerService.php` logic cutoff dan must saving
- `resources/views` Blade views
- `resources/css/app.css` design tokens dan komponen brutalist
- `database/migrations` schema aplikasi

## Catatan

- README ini menjelaskan implementasi project saat ini, bukan template default Laravel
- Spec lama masih ada untuk referensi, tapi UI aktif mengikuti brutalist implementation yang sudah dipakai di view
