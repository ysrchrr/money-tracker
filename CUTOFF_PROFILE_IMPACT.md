# Cutoff Per Profile

## Ringkasan

Perubahan yang diminta masuk akal:

- user bisa memilih apakah metode cutoff dipakai atau tidak
- ada penjelasan singkat soal cutoff periode `26 bulan lalu - 25 bulan ini`
- setting ini diletakkan di profile dengan opsi `Aktifkan: Ya / Tidak`
- tidak ada perubahan di alur register

Secara produk, ini lebih fleksibel karena tidak semua orang terbiasa lihat uang berdasarkan periode cutoff. Sebagian user lebih natural pakai bulan kalender biasa `1-akhir bulan`.

## Kenapa Ini Bagus

1. Lebih cocok untuk lebih banyak tipe user.
User payroll / budgeting sering suka cutoff. User personal biasa sering lebih paham bulan kalender.

2. Mengurangi friksi onboarding.
Kalau cutoff dipaksa global, user yang tidak paham bisa merasa angka dashboard "aneh" karena range tanggalnya tidak sama dengan bulan biasa.

3. Lebih aman secara UX.
Fitur advanced jadi opt-in, bukan behavior default yang membingungkan.

4. Lebih future-proof.
Kalau nanti mau ada beberapa pola periode lain, pondasinya sudah per-user, bukan global app setting.

## Rekomendasi Produk

Saran saya:

- default `Tidak`
- cutoff day tetap `25`
- setting disimpan di tabel `users`
- profile menampilkan penjelasan singkat:

`Jika aktif, periode laporan dihitung dari tanggal 26 bulan sebelumnya sampai 25 bulan berjalan. Cocok untuk gajian atau budgeting bulanan berbasis cutoff.`

Alasannya: cutoff itu fitur advanced. Lebih aman dijadikan pilihan sadar user, bukan default untuk semua.

## Route Yang Kena Imbas

### Wajib kena

- `GET /profile` -> `profile.edit`
- `PATCH /profile` -> `profile.update`

Karena toggle ada di halaman profile dan disimpan dari sana.

### Tidak perlu route baru

Secara struktur sekarang, tidak perlu route tambahan. Cukup pakai route profile yang sudah ada.

## File Yang Kena Imbas

### Wajib

- [app/Models/User.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Models/User.php)
  Tambah field user-level untuk cutoff, minimal boolean enable/disable, karena setting disimpan di tabel `users`.

- [database/migrations/0001_01_01_000000_create_users_table.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/database/migrations/0001_01_01_000000_create_users_table.php)
  Tidak diubah langsung kalau project sudah jalan, tapi akan butuh migration baru untuk tambah kolom ke tabel `users`.

- [app/Http/Requests/ProfileUpdateRequest.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Http/Requests/ProfileUpdateRequest.php)
  Validasi field cutoff profile.

- [app/Http/Controllers/ProfileController.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Http/Controllers/ProfileController.php)
  Simpan preference cutoff milik user.

- [resources/views/profile/partials/update-profile-information-form.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/profile/partials/update-profile-information-form.blade.php)
  Tambah UI penjelasan cutoff + radio/select `Ya / Tidak`.

- [resources/views/profile/edit.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/profile/edit.blade.php)
  Mungkin hanya terdampak layout kalau blok profile jadi lebih panjang.

- [app/Support/MoneyTrackerService.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Support/MoneyTrackerService.php)
  Ini pusat perubahan logic. Saat ini cutoff dibaca global dari `AppSetting`. Kalau dibuat per-profile, service harus baca setting milik user.

### Terdampak langsung oleh logic periode user

- [app/Http/Controllers/DashboardController.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Http/Controllers/DashboardController.php)
  Dashboard member pakai `periodForMonth()`, jadi pasti kena.

- [resources/views/dashboard.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/dashboard.blade.php)
  Perlu pastikan label period tetap jelas saat cutoff mati atau hidup.

- [app/Http/Controllers/CashFlowController.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Http/Controllers/CashFlowController.php)
  Kena kalau halaman cash flow member harus konsisten dengan mode cutoff user, terutama untuk summary, filter periode, atau definisi bulan aktif.

- [resources/views/cash-flows/index.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/cash-flows/index.blade.php)
  Kena kalau UI cash flow perlu menjelaskan apakah list/summary sedang memakai bulan kalender biasa atau periode cutoff user.

- [app/Http/Controllers/Admin/DashboardController.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Http/Controllers/Admin/DashboardController.php)
  Kena kalau admin dashboard harus menghormati cutoff per member. Ini agak tricky karena data admin agregat lintas user.

- [app/Http/Controllers/Admin/ReportController.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Http/Controllers/Admin/ReportController.php)
  Sama seperti admin dashboard, impact-nya tergantung definisi report lintas member.

### Optional / tergantung scope

- [resources/views/welcome.blade.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/resources/views/welcome.blade.php)
  Optional kalau landing page mau menjelaskan bahwa cutoff sekarang opsional.

- [app/Http/Controllers/HomeController.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Http/Controllers/HomeController.php)
  Optional kalau copy fitur di homepage mau diubah.

- [database/seeders/DatabaseSeeder.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/database/seeders/DatabaseSeeder.php)
  Optional tapi sebaiknya ikut disesuaikan supaya demo user punya state cutoff yang jelas.

## Catatan Teknis Penting

### 1. Setting cutoff sekarang harus pindah ke level user

Saat ini cutoff day diambil dari:

- [app/Support/MoneyTrackerService.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Support/MoneyTrackerService.php)
- [database/seeders/DatabaseSeeder.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/database/seeders/DatabaseSeeder.php)

melalui key app setting:

- `must_saving_cutoff_day`

Karena keputusan scope-nya adalah setting disimpan di tabel `users`, maka yang paling pas:

- `is_cutoff_enabled` per-user
- cutoff day tetap fixed `25`

### 2. Admin report akan jadi area paling sensitif

Kalau setiap user bisa punya cutoff on/off, report admin lintas member tidak lagi punya satu definisi periode yang sama.

Artinya harus dipilih salah satu:

1. admin report tetap pakai cutoff global tunggal
2. admin report mengikuti setting masing-masing member
3. fitur cutoff hanya memengaruhi dashboard member, bukan report admin

Menurut saya opsi paling aman untuk phase awal:

- cutoff per-profile hanya memengaruhi area member
- admin tetap pakai periode global / fixed

Kalau tidak, agregasi lintas member bisa rancu.

### 3. Cash flow member ikut terdampak kalau mau konsisten

- [app/Http/Controllers/CashFlowController.php](/Applications/XAMPP/xamppfiles/htdocs/money-tracker/app/Http/Controllers/CashFlowController.php)

Halaman cash flow sekarang filter berdasarkan bulan kalender dari field `month`, bukan period cutoff. Jadi:

- kalau cutoff hanya untuk dashboard, file ini bisa dibiarkan
- kalau cutoff ingin konsisten untuk area member, cash flow member ikut kena

Karena Anda sudah menegaskan cutoff ini berpengaruh ke dashboard member dan cash flows member, berarti controller dan view cash flow member perlu dihitung sebagai impact utama.

## Scope Implementasi Yang Paling Aman

Kalau nanti dieksekusi, saya sarankan scope pertama:

1. tambah kolom user: toggle cutoff enable/disable
2. tampilkan setting itu di profile
3. dashboard member membaca setting user
4. cash flow member membaca setting user
5. admin dashboard/report belum ikut diubah
6. register tetap seperti sekarang

Dengan scope itu, perubahan tetap besar tapi masih terkontrol.

## Kesimpulan

Kalau dibuat seperti ini, impact utamanya ada di:

- profile form dan update flow
- model user + migration baru di tabel `users`
- service period/cutoff berbasis user
- dashboard member
- cash flow member

Route baru tidak wajib. Route register juga tidak perlu diubah.
