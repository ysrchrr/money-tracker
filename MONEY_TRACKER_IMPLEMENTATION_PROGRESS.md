# Money Tracker - Implementation Progress

## Current Focus

Tahap ini fokus ke fitur inti yang harus benar-benar hidup dulu:

- user dengan `role` dan `member_type`
- CRUD `categories`
- CRUD `cash_flows`
- dashboard dengan data real
- `must savings` calculation
- `reminder_settings` dasar

## Current Status

### Selesai di tahap ini

- MySQL `money_tracker` aktif
- migration inti untuk:
  - `users`
  - `categories`
  - `cash_flows`
  - `app_settings`
  - `reminder_settings`
  - `whatsapp_notification_logs`
- `role` dan `member_type` sudah masuk ke `users`
- dashboard sudah baca data real
- CRUD `categories` sudah jalan
- CRUD `cash_flows` sudah jalan
- filter `cash_flows` per bulan, tipe, category, dan search sudah jalan
- kalkulasi `must savings` sudah hidup
- halaman `reminder` sudah jalan
- akses `reminder` dibatasi untuk `gold`
- seed demo sudah ada
- test fitur inti sudah ditambah

### Akun demo seed

- `admin@moneytracker.test` / `password`
- `member@moneytracker.test` / `password`

## What Was Implemented

### Dashboard

- filter period berbasis cutoff day
- summary:
  - income
  - expense
  - net
  - must saving
- recent transactions
- spending by category

### Categories

- create category
- update category
- delete category jika belum dipakai transaksi
- unique per user

### Cash Flows

- create income / expense
- category wajib untuk `expense`
- income otomatis tanpa category
- edit transaksi
- delete transaksi
- summary by current filter

### Reminder

- simpan nomor WhatsApp
- enable / disable reminder
- atur jam kirim
- atur template pesan
- `silver` melihat locked state

## Implementation Scope

### Dikerjakan sekarang

- migration dan model inti
- seed user awal
- halaman dashboard, cash flow, category, reminder
- validasi request
- rule akses dasar untuk `gold` reminder
- shell UI brutalism tetap dipakai

### Belum jadi target tahap ini

- impersonation readonly superadmin
- WhatsApp API integration real
- cron reminder harian
- notification logs penuh
- export/reporting
- AJAX CRUD layer
- menu members untuk superadmin

## Notes

- DB target: `mysql / money_tracker`
- pendekatan tahap ini: server-rendered Laravel yang fungsional dulu
- interaksi bisa ditingkatkan ke AJAX setelah flow bisnis inti stabil
- test status terakhir: `29 passed`
