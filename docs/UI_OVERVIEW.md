# UI Overview

## Ringkasan

UI Money Tracker memakai pendekatan `neo-brutalism` dengan gaya yang keras, kontras tinggi, dan fokus ke keterbacaan data finansial. Tujuannya bukan bikin dashboard yang halus, tapi interface yang terasa disiplin dan cepat dipakai.

## Karakter Visual

- border hitam tebal
- hard shadow tanpa blur
- heading uppercase dan berat
- blok warna solid
- bentuk kotak, minim radius
- layout editorial, tidak generik

## Design Tokens Utama

Base token di [resources/css/app.css](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/css/app.css:1).

Warna utama:

- `--bg-canvas: #fffdf5`
- `--surface: #ffffff`
- `--surface-2: #ffd93d`
- `--surface-3: #c4b5fd`
- `--accent: #ff6b6b`
- `--success: #7bd389`
- `--ink: #000000`

Komponen visual:

- border utama `4px`
- shadow keras `4px`, `8px`, `12px`
- font `Space Grotesk`
- canvas background pakai pattern grid dan dot

## Prinsip UI

- angka finansial harus paling dominan
- panel penting dibedakan lewat warna, bukan ornament
- form harus terasa padat dan langsung
- table harus mudah discan
- badge dipakai sebagai penanda state, periode, atau mode
- dark mode tetap brutal, bukan smooth mode

## Komponen Inti

### Panel

Varian panel utama:

- `.brutal-panel`
- `.brutal-panel-accent`
- `.brutal-panel-yellow`
- `.brutal-panel-violet`

Fungsinya:

- memisahkan section
- memberi hierarki visual yang cepat
- menjaga ritme layout

### Button

Class utama:

- `.brutal-btn`
- `.brutal-btn-secondary`
- `.brutal-btn-ghost`

Behavior:

- hover naik sedikit
- active turun menutup shadow
- teks uppercase, bold, rapat

### Form

Komponen form:

- `.brutal-input`
- `.brutal-select`
- `.brutal-textarea`
- `.brutal-label`

Behavior:

- fokus berubah jadi blok kuning
- tanpa glow halus
- tetap kontras di light dan dark

### Stat Block

Komponen:

- `.brutal-stat`
- `.brutal-stat-title`
- `.brutal-stat-value`

Dipakai untuk:

- income
- expense
- net
- must saving
- admin summary

### Table / Ledger

Komponen:

- `.brutal-ledger`

Ciri:

- table border penuh
- header kuning
- typography uppercase
- cocok untuk transaksi dan kategori

## Navigasi

Layout app utama ada di [resources/views/layouts/app.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/layouts/app.blade.php:1).

Struktur:

- sidebar kiri untuk desktop
- topbar sticky
- nav horizontal untuk mobile
- badge khusus untuk mode admin dan readonly impersonation

## Theme

Theme switch:

- simpan pilihan ke `localStorage`
- default ikut `prefers-color-scheme`
- diaktifkan lewat `data-theme="light|dark"` pada elemen `html`

Dark mode:

- tetap pakai warna brutalist
- tetap mempertahankan hard shadow
- tidak diubah jadi neon atau UI futuristik

## Mood Product

UI ini terasa seperti:

- ledger board
- papan kontrol keuangan
- poster editorial untuk angka dan status

Bukan:

- template admin halus
- landing page startup
- finance app generik warna biru
