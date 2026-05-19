# Money Tracker - Draft Specification

## Konsep

Aplikasi web-based untuk tracking uang pribadi/member. Ada 2 role:

- `superadmin`
- `member`

Tidak perlu approval flow atau fitur organisasi kompleks.

Stack target:

- Backend: Laravel
- Frontend: Blade + jQuery
- UI: shadcn-style components dengan Tailwind, tapi tetap server-rendered Laravel
- Database: MySQL

## Prinsip UI

- Bukan look generic SaaS.
- Gunakan layout sharp, editorial, sedikit brutalist.
- Warna disarankan: off-white, charcoal, olive, rust, muted gold.
- Hindari gradient biru/ungu dan shadow berlebihan.
- Komponen shadcn dipakai sebagai dasar struktur: table, dialog, input, button, select, badge.
- Interaksi form pakai AJAX supaya input cepat tanpa reload halaman.

## Menu

1. Dashboard
2. Cash Flow
3. Category
4. Reminder

Khusus superadmin:

5. Members

Optional later:

6. Setting

## Role & Access

### Superadmin

- Bisa melihat list member.
- Bisa melihat `member_type`: `silver` / `gold`.
- Bisa impersonate ke member dalam mode readonly.
- Saat impersonate:
    - Bisa lihat dashboard, cash flow, category, dan reminder member tersebut.
    - Tidak bisa create/update/delete data.
    - Semua tombol submit/edit/delete disembunyikan atau disabled.
    - Request mutation tetap harus diblokir dari backend.

### Member

- Bisa manage data sendiri.
- Bisa create/update/delete Cash Flow sendiri.
- Bisa create/update/delete Category sendiri.
- Bisa akses Reminder hanya jika `member_type = gold`.
- Tidak bisa akses data member lain.

### Member Type

- Default: `silver`
- Berbayar: `gold`
- Menu Reminder hanya aktif untuk member `gold`.
- Jika member turun dari `gold` ke `silver`, reminder otomatis dianggap inactive dan cron skip user tersebut.

### Data Ownership

- `cash_flows` wajib punya `user_id`.
- `categories` wajib punya `user_id`.
- `reminder_settings` wajib punya `user_id`.
- `whatsapp_notification_logs` wajib punya `user_id`.
- Query default selalu scoped ke user aktif.
- Saat superadmin impersonate readonly, query scoped ke selected member.

## Dashboard

Dashboard menampilkan ringkasan bulanan, bukan halaman dekoratif.

Komponen yang disarankan:

- Monthly Balance
    - Total income bulan ini
    - Total expense bulan ini
    - Net cash flow
- Must Savings
    - Total tabungan wajib hasil akumulasi persentase category berdasarkan periode cutoff
    - Breakdown per category
- Spending by Category
    - List category dengan nominal dan persentase kontribusi
- Recent Cash Flow
    - 5-10 transaksi terakhir
- Monthly Trend
    - Grafik sederhana income vs expense per bulan
- Superadmin Overview
    - Total uang tercatat dari semua member
    - Jumlah member
    - Total uang tercatat hari ini

Default filter dashboard:

- Bulan berjalan berdasarkan periode cutoff Must Savings
- Bisa ubah bulan/tahun
- Untuk superadmin, dashboard default menampilkan aggregate semua member dan bisa pilih member tertentu.
- Saat impersonate, dashboard hanya menampilkan data member yang sedang dilihat.

## Landing Page

Default route `/` diarahkan ke landing page public.

Tujuan halaman:

- Menjelaskan bahwa Money Tracker adalah aplikasi untuk mencatat pemasukan, pengeluaran, category spending, dan Must Savings.
- Menampilkan fitur utama secara ringkas.
- Memberi entry point ke login/register.

Konten utama:

- Apa itu Money Tracker
    - Aplikasi pencatatan cash flow personal/member.
    - Fokus ke pencatatan cepat, kalkulasi Must Savings, dan reminder harian.
- Fitur:
    - Cash Flow AJAX tanpa reload.
    - Category dengan percentage untuk Must Savings.
    - Dashboard ringkasan bulanan.
    - Cutoff Must Savings.
    - Daily WhatsApp Reminder untuk member gold.
    - Superadmin readonly impersonation.
- CTA:
    - Login
    - Register / Join

Catatan UI:

- Tetap bukan startup landing page generic.
- Hero boleh bold/editorial, tapi jangan terlalu dekoratif.
- Fokus copy harus jelas dan langsung.
- Gunakan visual fitur nyata: summary, table, reminder, bukan ilustrasi abstrak.

## Cash Flow

Menu utama untuk mencatat transaksi keuangan.

### Tampilan

Data ditampilkan dengan datatable.

Fitur table:

- Search
- Filter tanggal/bulan
- Filter jenis
- Filter category
- Sort by date terbaru
- Pagination
- Action edit/delete inline atau via dialog

Summary card di halaman Cash Flow:

- Total Income
- Total Expense
- Net Cash Flow
- Total Category
    - Jumlah category aktif/terpakai pada periode filter
- Must Saving This Month
    - Total Must Savings untuk bulan aktif berdasarkan cutoff

### Input

Field:

- Tanggal
    - Default: hari ini
    - Bisa diubah manual
- Keterangan
    - Contoh: `Beli Kopi`
- Jenis
    - Pilihan tetap: `income`, `expense`
- Category
    - Hanya muncul/wajib untuk `expense`
    - Ambil dari menu Category
    - Contoh: `fnb`
- Nominal
    - Format Rupiah di UI
    - Simpan sebagai integer/decimal di database

Input khusus `income`:

- Keterangan
- Nominal

Catatan: tanggal income otomatis hari ini.

Input khusus `expense`:

- Tanggal
- Keterangan
- Jenis
- Category
- Nominal

### Behavior

- Submit pakai AJAX.
- Setelah submit sukses:
    - Form tetap di halaman.
    - Table reload tanpa full page reload.
    - Summary dashboard/counter bisa ikut refresh jika ada di halaman.
- Validasi error muncul inline.
- Edit dan delete juga pakai AJAX.
- Delete sebaiknya pakai confirmation dialog.
- Jika sedang readonly impersonation, semua action create/update/delete disabled dan backend menolak request mutation.

### Contoh

Category:

- Name: `fnb`
- Percentage: `20`

Cash flow:

- Tanggal: `2026-05-01`
- Keterangan: `Beli Kopi`
- Jenis: `expense`
- Category: `fnb`
- Nominal: `25000`

Must Savings:

```text
25000 x 20% = 5000
```

## Category

Menu reference untuk pengelompokan transaksi dan dasar kalkulasi Must Savings.

### Field

- Category Name
    - Required
    - Unique
    - Contoh: `fnb`, `transport`, `home`, `health`
- Percentage
    - Required
    - Angka 0-100
    - Dipakai untuk kalkulasi Must Savings

### Tampilan

- Table category
- Form create/update via dialog atau inline panel
- Delete hanya boleh jika category belum dipakai transaksi, atau pakai soft delete

## Reminder

Menu untuk mengatur daily reminder via WhatsApp.

Akses:

- Hanya member dengan `member_type = gold`.
- Superadmin bisa lihat saat impersonate readonly.
- Member `silver` tidak melihat menu ini.

### Field

- WhatsApp Number
    - Nomor tujuan notifikasi.
- Is Enabled
    - Toggle on/off reminder.
- Send Time
    - Jam target pengiriman harian.
    - Default bisa `08:00`.
- Message Template
    - Template pesan reminder.
    - Bisa default dari sistem.

### Behavior

- Reminder bisa on/off.
- Daily notification dikirim lewat API WhatsApp yang sudah tersedia.
- Pengiriman dilakukan via route trigger supaya bisa dipanggil cron eksternal.
- Cron external target: `https://cron-job.org/`.
- Route trigger harus pakai secret token supaya tidak bisa dipanggil bebas.
- Cron trigger hanya memproses:
    - Member `gold`
    - Reminder `enabled`
    - Jadwal yang sudah masuk waktu kirim
    - Belum pernah sukses terkirim pada tanggal yang sama
- Semua attempt integrasi WhatsApp wajib dicatat di log, baik berhasil maupun gagal.

### Log WhatsApp

Setiap request ke API WhatsApp menyimpan:

- User/member tujuan
- Nomor WhatsApp
- Payload/message
- Response API
- Status: `success` atau `failed`
- Error message jika gagal
- Trigger source: `cron` atau `manual`
- Waktu request

## Setting

Belum perlu jadi menu utama di versi awal, tapi reference `jenis` perlu sumber data.

Opsi implementasi:

- Seed default transaction types:
    - `income`
    - `expense`
- Default cutoff Must Savings:
    - `25`
- Nanti bisa dibuat menu Setting jika butuh custom type.

## Struktur Data Draft

### users

Laravel default user table dengan tambahan role.

```text
id
name
email
password
role
member_type
created_at
updated_at
```

Role:

```text
superadmin
member
```

Member type:

```text
silver
gold
```

Catatan:

- Role sederhana cukup pakai enum/string column.
- Belum perlu table `roles` dan `permissions`.
- `member_type` default `silver`.
- `member_type = gold` untuk member berbayar.

### transaction_types

```text
id
name
slug
created_at
updated_at
```

Data awal:

```text
income
expense
```

### categories

```text
id
user_id
name
percentage
created_at
updated_at
deleted_at
```

Catatan:

- Category milik masing-masing member.
- `name` unique per member, bukan global.
- `percentage` disimpan decimal, contoh `20.00`.
- `percentage` hanya berlaku untuk transaksi `expense`.
- Soft delete disarankan supaya transaksi lama tetap aman.

### cash_flows

```text
id
user_id
transaction_date
description
transaction_type_id
category_id
amount
created_at
updated_at
deleted_at
```

Catatan:

- Cash flow milik masing-masing member.
- `amount` disimpan positif.
- Arah income/expense ditentukan dari `transaction_type_id`.
- `category_id` required untuk expense.
- Untuk income, `category_id` null.
- `saving_month` tidak wajib disimpan; bisa dihitung dari `transaction_date` + cutoff.

### app_settings

```text
id
key
value
created_at
updated_at
```

Data awal:

```text
must_saving_cutoff_day = 25
daily_reminder_cron_token = random-secret-token
```

### impersonation_logs

Optional, tapi disarankan untuk audit.

```text
id
superadmin_id
member_id
started_at
ended_at
created_at
updated_at
```

### reminder_settings

```text
id
user_id
whatsapp_number
is_enabled
send_time
message_template
last_sent_at
created_at
updated_at
```

Catatan:

- Satu member cukup punya satu reminder setting.
- `is_enabled` default `false`.
- Cron skip reminder jika user bukan `gold`.
- `last_sent_at` membantu mencegah duplicate send dalam 1 hari.

### whatsapp_notification_logs

```text
id
user_id
reminder_setting_id
whatsapp_number
message
payload
response_body
status
error_message
trigger_source
sent_at
created_at
updated_at
```

Status:

```text
success
failed
```

Trigger source:

```text
cron
manual
```

## Kalkulasi Must Savings

Rumus per transaksi:

```text
must_saving = amount x category.percentage / 100
```

Akumulasi bulanan:

```text
monthly_must_savings = SUM(must_saving) dari transaksi expense pada saving month terpilih
```

## Cutoff Must Savings

Default cutoff: tanggal `25`.

Aturan attribution bulan:

```text
Jika tanggal transaksi <= cutoff day:
    saving_month = bulan transaksi

Jika tanggal transaksi > cutoff day:
    saving_month = bulan berikutnya
```

Contoh cutoff `25`:

```text
2026-03-01 sampai 2026-03-25 => Must Savings Maret 2026
2026-03-26 sampai 2026-03-31 => Must Savings April 2026
2026-04-01 sampai 2026-04-25 => Must Savings April 2026
2026-04-26 sampai 2026-04-30 => Must Savings Mei 2026
```

Jadi periode Must Savings April 2026:

```text
2026-03-26 sampai 2026-04-25
```

Contoh:

```text
Beli Kopi    fnb 20%      25.000  => 5.000
Makan Siang  fnb 20%      40.000  => 8.000
Gojek        transport 10% 30.000 => 3.000

Total Must Savings = 16.000
```

## Route Draft

```text
GET    /
GET    /dashboard

GET    /members
POST   /members/{member}/impersonate
DELETE /members/impersonate

GET    /cash-flows
POST   /cash-flows
GET    /cash-flows/{id}
PUT    /cash-flows/{id}
DELETE /cash-flows/{id}

GET    /categories
POST   /categories
GET    /categories/{id}
PUT    /categories/{id}
DELETE /categories/{id}

GET    /reminder
PUT    /reminder
POST   /reminder/test-send

GET    /cron/reminders/daily-whatsapp?token={secret}
```

Catatan cron route:

- Route ini dipanggil oleh `https://cron-job.org/`.
- Tidak pakai session login.
- Wajib validasi `token`.
- Response cukup JSON ringkas berisi total processed, success, failed, skipped.
- Tetap idempotent per user per tanggal supaya cron retry tidak mengirim dobel.

AJAX response format:

```json
{
    "success": true,
    "message": "Saved",
    "data": {}
}
```

Validation error:

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {}
}
```

## Page Layout Draft

### Landing Page Layout

- Public page tanpa sidebar.
- Header simple:
    - Brand/name
    - Login
    - Register / Join
- Hero editorial:
    - Headline menjelaskan produk.
    - Short description.
    - CTA login/register.
- Feature section:
    - Cash Flow
    - Must Savings
    - WhatsApp Reminder
    - Superadmin View
- Pricing/member type preview:
    - Silver: basic tracking.
    - Gold: tracking + WhatsApp Reminder.

### Base Layout

- Sidebar kiri compact
- Main content lebar penuh
- Header kecil berisi current month selector
- No marketing hero

### Cash Flow Layout

- Kiri/atas: quick input form
- Kanan/bawah: datatable
- Summary strip:
    - Income
    - Expense
    - Net
    - Total Category
    - Must Savings
- Saat readonly impersonation, tampilkan readonly badge di header.

### Category Layout

- Table category
- Create/edit form dalam dialog atau side panel

### Reminder Layout

- Form setting WhatsApp number.
- Toggle reminder on/off.
- Input send time.
- Textarea message template.
- Button test send.
- Table log pengiriman terakhir.
- Untuk member `silver`, tampilkan locked state atau redirect.

## MVP Scope

Masuk MVP:

- Public landing page sebagai default route `/`
- Login Laravel default/simple auth
- Role superadmin/member
- Member data ownership
- Superadmin readonly impersonation
- Dashboard monthly summary
- CRUD Cash Flow AJAX
- CRUD Category AJAX
- Reminder setting untuk member gold
- Cron trigger route untuk daily WhatsApp reminder
- WhatsApp notification log
- Seeder transaction type
- Must Savings monthly calculation dengan cutoff

Tidak masuk MVP:

- Permission kompleks
- Budgeting kompleks
- Export PDF/Excel
- Recurring transaction
- Bank sync
- Notification selain WhatsApp daily reminder

## Fixed Decision

- Income cukup input keterangan dan nominal.
- Income tidak memakai category.
- Jenis transaksi hanya `income` dan `expense`.
- Percentage category hanya berlaku untuk `expense`.
- Superadmin bisa melihat aggregate semua member:
    - Total uang tercatat dari semua member
    - Jumlah member
    - Total uang tercatat hari ini
- Reminder hanya untuk member `gold`.
- Member default adalah `silver`.
- WhatsApp notification wajib punya log sukses/gagal.
- Default route `/` adalah landing page public.
