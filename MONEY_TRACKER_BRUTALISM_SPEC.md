# Money Tracker - Neo-Brutalism UI Spec

## Tujuan Dokumen

Dokumen ini menggantikan arah UI pada `MONEY_TRACKER_SPEC.md` yang sebelumnya condong ke `shadcn-style components`.

Fokus dokumen ini:

- mempertahankan fondasi produk money tracker yang sudah benar
- mengganti visual system menjadi `neo-brutalism`
- memastikan UI tetap cepat dipakai untuk input transaksi, bukan cuma tampil "unik"

Dokumen lama tetap bisa dipakai untuk referensi fitur, role, data model, dan route draft.

## Posisi Revisi

Yang tetap dipakai dari spec lama:

- stack `Laravel + Blade + jQuery + MySQL`
- role `superadmin` dan `member`
- fitur `dashboard`, `cash flow`, `category`, `reminder`, `members`
- behavior `AJAX` untuk form dan table refresh
- kalkulasi `Must Savings`
- readonly impersonation untuk superadmin

Yang diubah:

- buang arah `shadcn-style`
- jangan jadikan UI seperti dashboard SaaS modern yang terlalu halus
- jangan pakai rounded card, soft shadow, atau gradient generik
- semua halaman harus memakai visual language brutalist yang konsisten

## Product Mood

Money Tracker ini sebaiknya terasa seperti:

- buku kas personal yang keras dan tegas
- dashboard finansial yang "nyuruh disiplin"
- perpaduan ledger, notice board, dan poster editorial

Bukan terasa seperti:

- startup finance app yang lembut
- admin panel template
- landing page SaaS generik

## Core Visual Direction

### Karakter

- keras
- padat
- kontras tinggi
- sedikit liar tapi tetap terstruktur
- tactile, seperti elemen ditempel di papan

### Prinsip Utama

1. Semua elemen penting harus punya border hitam yang jelas.
2. Shadow harus keras dan kotak, tanpa blur.
3. Typography harus dominan dan berani.
4. Warna dipakai sebagai blok sinyal, bukan dekorasi halus.
5. Layout boleh asimetris, tapi alur pakai harus tetap cepat dibaca.
6. Brutalism dipakai untuk menegaskan fungsi, bukan bikin chaos total.

## Design Tokens

### Color Tokens

- `--bg-canvas: #FFFDF5`
- `--ink: #000000`
- `--accent-red: #FF6B6B`
- `--accent-yellow: #FFD93D`
- `--accent-violet: #C4B5FD`
- `--surface-white: #FFFFFF`

### Dark Mode Tokens

Dark mode bukan versi "smooth modern app".

Dark mode tetap harus terasa brutalist:

- kontras tinggi
- border tetap keras
- shadow tetap kotak
- warna aksen tetap meledak

Token awal yang disarankan:

- `--dark-bg-canvas: #111111`
- `--dark-surface: #1C1C1C`
- `--dark-ink: #FFFDF5`
- `--dark-accent-red: #FF6B6B`
- `--dark-accent-yellow: #FFD93D`
- `--dark-accent-violet: #C4B5FD`
- `--dark-border: #FFFDF5`

### Usage Rules

- background utama gunakan `cream`, bukan putih polos
- text utama selalu `black`
- merah untuk aksi utama, warning, expense, state penting
- kuning untuk highlight, summary, quick info
- violet untuk panel sekunder, filter area, dan support section
- hindari abu-abu lembut

Untuk dark mode:

- background utama gunakan `#111111`
- panel gunakan `#1C1C1C` atau blok warna aksen
- text utama gunakan `cream` atau `white`
- border dan hard shadow gunakan `cream` atau `white`, bukan abu
- jangan ubah dark mode jadi neon cyberpunk; tetap editorial dan keras

### Border Tokens

- default border: `4px solid #000`
- divider penting: `6px` sampai `8px`
- radius default: `0`
- `rounded-full` hanya untuk badge tertentu

### Shadow Tokens

- small: `4px 4px 0 #000`
- medium: `8px 8px 0 #000`
- large: `12px 12px 0 #000`

Semua shadow:

- tanpa blur
- tanpa opacity lembut
- selalu offset kanan bawah

Untuk dark mode:

- gunakan hard shadow warna `#FFFDF5` atau `#FFFFFF` pada panel gelap tertentu bila perlu
- pada blok warna terang di atas background gelap, shadow tetap boleh hitam agar layering tetap terbaca

### Typography Tokens

- font utama: `Space Grotesk`, fallback `sans-serif`
- heading: `900`
- body dan button: `700`
- label kecil: uppercase, tracking lebar

## Interaction Principles

## Dark Mode Strategy

Dark mode harus diposisikan sebagai visual mode resmi, bukan afterthought.

### Tujuan

- nyaman dipakai malam hari
- tetap menjaga identitas brutalism
- tidak mengorbankan keterbacaan angka, tabel, dan form

### Switch Behavior

- sediakan switch `Light / Dark` di topbar
- state disimpan di `localStorage`
- hormati `prefers-color-scheme` untuk default awal jika user belum memilih manual
- pilihan user manual selalu override system preference

### Komponen yang Harus Dicek Saat Dark Mode

- summary block
- sidebar
- topbar
- quick add form
- ledger table
- badge status
- filter controls
- locked state untuk member `silver`

### Aturan Visual Dark Mode

- jangan sekadar invert semua warna
- pertahankan hierarchy warna: merah, kuning, violet tetap punya fungsi yang sama
- angka nominal harus tetap paling dominan
- border harus tetap sangat terlihat
- focus state harus tetap kuat tanpa glow lembut
- table row harus tetap mudah discan

### Preferensi Implementasi Nanti

- gunakan attribute seperti `data-theme="dark"` pada `<html>` atau `<body>`
- semua token warna utama disiapkan untuk mode terang dan gelap
- jangan duplikasi style per komponen jika bisa diturunkan dari token
- komponen shared harus theme-aware dari awal

### Buttons

- bentuk kotak tegas
- border hitam tebal
- shadow keras
- saat ditekan, tombol harus turun menutup shadow
- hover cepat dan terasa mekanis

### Cards and Panels

- jangan pakai "floating modern card"
- panel harus terasa seperti kertas tebal atau papan tempel
- hover boleh sedikit naik kalau memang interaktif

### Inputs

- besar, jelas, tebal
- fokus ubah background atau shadow, bukan glow biru
- nominal harus jadi titik perhatian utama di form transaksi

## Layout Strategy

### Global

- app layout berbasis sidebar kiri + content area kanan
- topbar kecil untuk filter bulan, user state, dan impersonation badge
- section dipisah dengan blok warna, divider tebal, atau panel besar

### Density

- dashboard harus padat informasi
- jangan kebanyakan whitespace kosong
- isi area kosong dengan pattern ringan, label besar, atau supporting meta

### Controlled Chaos

- boleh ada sedikit rotasi pada badge, label, dan heading block
- jangan rotasi table, input, atau area yang butuh presisi tinggi
- dekorasi dipakai hemat, fokus tetap ke data

## Halaman Utama

### Landing Page

Landing page harus menjual fungsi, bukan visual gimmick.

Struktur yang disarankan:

- hero keras dengan headline langsung
- preview summary money tracker yang nyata
- blok fitur utama
- perbandingan `silver` vs `gold`
- CTA login/register

Hero copy sebaiknya langsung, misalnya:

- `TRACK YOUR MONEY BEFORE IT DISAPPEARS`
- `catat pemasukan, pengeluaran, dan must savings tanpa ribet`

Landing page tidak boleh terasa seperti startup hero biasa.

### Dashboard

Dashboard adalah pusat kontrol, bukan halaman sambutan.

Komponen visual utama:

- saldo bulan ini sebagai headline besar
- blok `income`, `expense`, `net`, `must savings`
- spending by category dalam bentuk list/bar keras
- recent transactions seperti ledger
- monthly trend sederhana

Untuk superadmin:

- aggregate block harus terasa berbeda dari member mode
- saat impersonate, tampilkan badge readonly besar dan jelas

### Cash Flow

Halaman ini paling penting dan harus paling usable.

Struktur yang disarankan:

- quick input panel sangat dominan
- summary strip di atas
- transaction ledger di bawah atau kanan
- filter jangan tersebar liar, satukan dalam satu control band

Karakter visual:

- nominal besar
- toggle `income / expense` seperti switch keras
- category berupa pilihan yang jelas, bukan select lembek
- table lebih cocok tampil seperti ledger/slip daripada card list

### Category

Category page harus terasa seperti tempat mengatur aturan uang.

Elemen:

- daftar category
- percentage besar dan mudah discan
- form create/edit ringkas
- warning jika category sudah dipakai transaksi

### Reminder

Reminder page cocok dibuat seperti "control board".

Elemen:

- nomor WhatsApp
- status on/off
- jam kirim
- template pesan
- log kirim terakhir

Member `silver`:

- tampilkan state terkunci yang tegas
- bukan sekadar hidden diam-diam

## Component Direction

### Summary Blocks

Jangan buat sebagai card SaaS generik.

Harus:

- border hitam tebal
- background warna blok
- angka sangat dominan
- label kecil uppercase

### Tables

Gunakan tampilan seperti ledger:

- header tebal
- row rapat tapi tetap nyaman
- garis pemisah jelas
- nominal rata kanan dan menonjol
- action edit/delete terlihat tegas

### Badges

Cocok untuk:

- `readonly`
- `gold`
- `silver`
- `over budget`
- `today`

Badge boleh sedikit rotated, tapi jangan berlebihan.

### Empty States

Harus tetap punya karakter.

Contoh arah:

- `BELUM ADA TRANSAKSI`
- `TAMBAH CATATAN PERTAMA HARI INI`

Jangan pakai ilustrasi abstrak generik.

## Visual Anti-Patterns

Yang harus dihindari:

- rounded card modern
- shadow blur
- glassmorphism
- gradient biru/ungu
- icon-heavy UI
- terlalu banyak dekorasi sampai data kalah
- layout marketing template

## Accessibility Guardrails

- kontras teks harus tetap tinggi
- semua button dan link tetap jelas saat focus keyboard
- warna tidak boleh jadi satu-satunya penanda income/expense
- ukuran tap target cukup besar, terutama di quick input
- motion tetap cepat, tapi jangan ganggu user sensitif gerakan

## Rekomendasi Implementasi Nanti

Saat masuk fase coding, arah implementasi UI sebaiknya:

- tetap `Blade + jQuery`
- gunakan `SCSS` atau CSS modular sederhana
- centralize token di satu file
- buat partial reusable untuk:
  - sidebar
  - summary block
  - ledger row
  - brutal button
  - brutal input
  - badge
  - filter band

## Keputusan Pengganti untuk Spec Lama

Baris ini di `MONEY_TRACKER_SPEC.md` tidak dipakai lagi sebagai arah UI:

- `UI: shadcn-style components dengan Tailwind`
- `Komponen shadcn dipakai sebagai dasar struktur`

Penggantinya:

- UI system utama adalah `neo-brutalism`
- styling dibangun custom mengikuti kebutuhan produk
- fokus pada speed of input, visual discipline, dan identitas kuat

## Output yang Sebaiknya Dibuat Setelah Ini

Setelah dokumen ini, urutan kerja yang paling masuk:

1. `MONEY_TRACKER_SITEMAP.md`
2. `MONEY_TRACKER_UI_INVENTORY.md`
3. `MONEY_TRACKER_WIREFRAME_NOTES.md`
4. `MONEY_TRACKER_DESIGN_TOKENS.md`

Dokumen ini sengaja belum masuk ke HTML, Blade, atau CSS implementasi.
