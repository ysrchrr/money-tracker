# UI Pages

## Landing Page

Referensi: [resources/views/welcome.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/welcome.blade.php:1)

Struktur:

- header dengan logo, toggle theme, login, register
- hero dua kolom
- preview statistik bulan berjalan
- blok fitur utama
- blok plan `silver` dan `gold`

Catatan visual:

- headline besar dan agresif
- preview dashboard dipakai sebagai bukti fungsi
- CTA tetap sederhana dan keras

## Dashboard Member

Referensi: [resources/views/dashboard.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/dashboard.blade.php:1)

Struktur:

- header periode
- hero net cash
- filter bulan
- empat stat block utama
- spending by category
- recent ledger

Catatan visual:

- angka `net cash` jadi fokus utama halaman
- warna merah, kuning, putih, violet dipakai sebagai sinyal cepat
- section kategori dan transaksi dipisah panel besar

## Cash Flow

Referensi: [resources/views/cash-flows/index.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/cash-flows/index.blade.php:1)

Struktur:

- info periode aktif
- summary transaksi
- form input atau edit transaksi
- panel filter
- ledger transaksi

Elemen penting:

- type switch `expense/income`
- nominal dibuat menonjol
- table transaksi punya action `edit` dan `delete`
- readonly mode menyembunyikan area mutasi

## Category

Referensi: [resources/views/categories/index.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/categories/index.blade.php:1)

Struktur:

- form create/edit kategori
- table daftar kategori
- kolom persentase must saving
- kolom jumlah transaksi

Catatan visual:

- halaman ini lebih sederhana karena sifatnya reference data
- penekanan ada di nama kategori dan persentase

## Reminder

Referensi: [resources/views/reminder/index.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/reminder/index.blade.php:1)

Struktur:

- form setting reminder
- status akses `gold`, `silver`, atau readonly
- panel current status
- log pengiriman terakhir

State penting:

- member `silver` dapat tampilan locked
- admin impersonation tampil readonly
- member `gold` dapat form aktif penuh

## App Shell

Referensi: [resources/views/layouts/app.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/layouts/app.blade.php:1)

Struktur global:

- sidebar desktop
- topbar sticky
- nav mobile horizontal
- badge mode admin
- badge readonly impersonation
- toast area

Tujuan:

- semua halaman punya frame visual yang konsisten
- status user langsung terlihat tanpa perlu baca detail

## Admin Dashboard

Referensi: [resources/views/admin/dashboard.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/admin/dashboard.blade.php:1)

Struktur:

- hero global net
- filter periode
- stat total member, active member, expense, must saving
- ranking member
- category pressure
- transaksi global terbaru

Catatan visual:

- versi admin tetap satu bahasa visual dengan member area
- bedanya ada badge dan informasi agregat lintas member

## Pola Konsisten Antar Halaman

- semua judul pakai uppercase tebal
- panel selalu pakai border tebal dan hard shadow
- warna aksen dipakai untuk identitas section
- table dipakai sebagai ledger, bukan sekadar data grid biasa
- mobile tetap dipertahankan lewat nav horizontal dan panel bertumpuk
