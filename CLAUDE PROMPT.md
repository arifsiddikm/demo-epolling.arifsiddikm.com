# CLAUDE PROMPT — e-Polling (PRD Lengkap)

> Upload file ini ke sesi Claude baru dan minta: **"Bantu saya build website e-Polling sesuai PRD ini dari awal."**

---

## Identitas Proyek

- **Nama Proyek:** e-Polling
- **Domain Demo:** https://demo-epolling.arifsiddikm.com
- **Konsep:** Platform polling publik berbasis web. User (yang punya akun) bisa membuat polling bebas. Polling bisa diisi oleh siapa saja (publik) melalui link unik tanpa perlu login.

---

## Tech Stack

| Layer     | Teknologi                                      |
|-----------|------------------------------------------------|
| Backend   | PHP 8.3 + Laravel 12                           |
| Database  | MySQL (atau SQLite untuk development)          |
| Frontend  | Tailwind CSS CDN + Alpine.js + SweetAlert2 CDN |
| Export    | maatwebsite/excel + barryvdh/laravel-dompdf    |
| Auth      | Laravel session-based (bukan Breeze/Jetstream) |

---

## Struktur Peran (Role)

| Role  | Akses                                                               |
|-------|---------------------------------------------------------------------|
| admin | Panel `/webmin` — kelola semua user, semua polling, export global   |
| user  | Panel `/dashboard` — CRUD polling milik sendiri, export per polling |
| publik| Akses `/p/{slug}` — isi & lihat hasil polling tanpa login           |

---

## Database Schema

### Tabel `users`
```
id, name, email, email_verified_at, password, phone (nullable),
avatar (nullable), role (enum: admin/user, default: user),
is_active (boolean, default: true), remember_token, timestamps
```

### Tabel `polls`
```
id, user_id (FK users), title, description (nullable),
image (nullable, path storage), slug (unique),
primary_key_label (string, contoh: "NIM / NIK / ID"),
primary_key_placeholder (string, contoh: "Masukkan NIM Anda"),
is_active (boolean, default: true),
start_date (timestamp, nullable), end_date (timestamp, nullable),
timestamps
```

### Tabel `poll_options`
```
id, poll_id (FK polls, cascade delete), name, image (nullable, path storage),
description (nullable), order (integer, default: 0), timestamps
```

### Tabel `poll_votes`
```
id, poll_id (FK polls, cascade delete),
poll_option_id (FK poll_options, cascade delete),
voter_key (string — NIM/NIK/email/etc, unik per poll_id),
voter_name (nullable), ip_address (nullable), timestamps

UNIQUE INDEX: (poll_id, voter_key) — cegah double vote
```

---

## Routes

```
GET  /                         → landing page (view: landing)
GET  /login                    → form login (guest)
POST /login                    → proses login
GET  /register                 → form register (guest)
POST /register                 → proses register
POST /logout                   → logout (auth)

GET  /p/{slug}                 → cek status polling, tampil form input voter_key
POST /p/{slug}/check           → validasi voter_key, redirect ke form vote
POST /p/{slug}/vote            → simpan suara (AJAX/JSON)
GET  /p/{slug}/result          → halaman hasil polling

--- dashboard (auth) ---
GET  /dashboard                → dashboard user
GET  /dashboard/profile        → form edit profil
PUT  /dashboard/profile        → update profil (nama, telepon, avatar)
PUT  /dashboard/profile/password → update password

GET  /dashboard/polls          → list polling milik user
GET  /dashboard/polls/create   → form buat polling
POST /dashboard/polls          → simpan polling baru
GET  /dashboard/polls/{id}     → detail polling (statistik, grafik)
GET  /dashboard/polls/{id}/edit → form edit polling
PUT  /dashboard/polls/{id}     → update polling
DELETE /dashboard/polls/{id}   → hapus polling
PATCH /dashboard/polls/{id}/toggle → toggle is_active
GET  /dashboard/polls/{id}/recap → rekap suara lengkap (tabel voter)
GET  /dashboard/polls/{id}/export-pdf   → export PDF rekap
GET  /dashboard/polls/{id}/export-excel → export Excel rekap
GET  /dashboard/export/summary-pdf      → export PDF semua polling user
GET  /dashboard/export/summary-excel    → export Excel semua polling user

--- webmin (auth + admin middleware) ---
GET  /webmin                   → dashboard admin (statistik global)
GET  /webmin/users             → list semua user
GET  /webmin/users/{id}        → detail user + polling-nya
PATCH /webmin/users/{id}/toggle → toggle is_active user
DELETE /webmin/users/{id}      → hapus user

GET  /webmin/polls             → list semua polling
GET  /webmin/polls/{id}        → detail polling
PATCH /webmin/polls/{id}/toggle → toggle is_active polling
DELETE /webmin/polls/{id}      → hapus polling

GET  /webmin/account           → form akun admin
PUT  /webmin/account           → update akun admin
PUT  /webmin/account/password  → update password admin

GET  /webmin/export/polls-pdf     → export PDF semua polling
GET  /webmin/export/polls-excel   → export Excel semua polling
GET  /webmin/export/users-pdf     → export PDF semua user
GET  /webmin/export/users-excel   → export Excel semua user
```

---

## Fitur Detail

### Halaman Publik

**Landing Page (`/`)**
- Hero section: judul "e-Polling", deskripsi singkat, CTA login/register
- Fitur unggulan: Buat polling, bagikan link, lihat hasil real-time
- Footer sederhana

**Form Voter Key (`/p/{slug}`)**
- Tampil judul & deskripsi polling
- Input voter_key dengan label & placeholder kustom per polling
- Jika polling tidak aktif → tampil halaman "Polling Ditutup"
- Jika sudah pernah vote → tampil pesan "Anda sudah memilih"

**Halaman Vote (`/p/{slug}/check` → view)**
- Tampil opsi-opsi dengan foto & deskripsi
- Pilih satu opsi, submit via AJAX (fetch/axios)
- Validasi session agar voter_key harus melewati /check dulu
- Setelah berhasil → redirect ke halaman hasil

**Halaman Hasil (`/p/{slug}/result`)**
- Tampil grafik batang atau donat per opsi
- Tampil persentase & jumlah suara per opsi
- Total voter keseluruhan

---

### User Dashboard

**Dashboard**
- Statistik: total polling dibuat, total suara diterima, polling aktif
- Tabel polling terbaru

**Buat Polling (`/dashboard/polls/create`)**
- Input: judul, deskripsi (textarea), gambar polling (upload)
- Konfigurasi voter key: label dan placeholder
- Toggle is_active, tanggal mulai & selesai (datetime-local)
- Tambah opsi dinamis (JavaScript): nama, gambar, deskripsi
  - Minimal 2 opsi, bisa tambah/hapus opsi
- Tombol simpan

**Edit Polling**
- Sama seperti buat, tapi opsi yang sudah ada tidak bisa diedit/hapus dari form edit (untuk menjaga integritas suara)
- Hanya bisa edit info polling utama (judul, deskripsi, tanggal, status)

**Detail Polling (`/dashboard/polls/{id}`)**
- Info lengkap polling
- Grafik hasil (Chart.js atau inline SVG)
- Tabel suara masuk per opsi
- Tombol: edit, toggle status, rekap, export, hapus, copy link

**Rekap Suara**
- Tabel: No, Voter Key, Opsi Dipilih, Waktu Vote
- Search/filter
- Tombol export PDF & Excel

---

### Admin Panel

**Dashboard `/webmin`**
- Kartu statistik: total user, total polling, total suara, polling aktif
- Tabel polling terbaru, user terbaru

**Kelola User**
- Tabel: nama, email, role, total polling, status (aktif/nonaktif), aksi
- Tombol toggle status (aktifkan/nonaktifkan)
- Hapus user (dengan konfirmasi SweetAlert)
- Detail user: profil + daftar polling miliknya

**Kelola Polling**
- Tabel: judul, pemilik, total opsi, total suara, status, aksi
- Toggle status, hapus, lihat detail

---

## UI/UX Notes

- Warna utama: biru (indigo-600 atau blue-600 Tailwind)
- Semua konfirmasi delete/toggle menggunakan SweetAlert2
- Notifikasi sukses/error menggunakan flash session + tampilkan di UI
- Tabel panjang menggunakan paginate (10 per halaman)
- Responsive mobile-first
- Sidebar untuk panel user & admin (collapsible di mobile)
- Avatar default jika tidak upload: initial nama (CSS/JS)

---

## Middleware

```php
// AdminMiddleware: cek role == 'admin'
// Daftar di bootstrap/app.php:
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias(['admin' => AdminMiddleware::class]);
})
```

---

## Policy

```php
// PollPolicy: user hanya bisa view/update/delete polling miliknya sendiri
// Gunakan $this->authorize('view', $poll) di controller
```

---

## Export

- **PDF:** Gunakan view blade khusus (`resources/views/exports/*.blade.php`) + DomPDF
- **Excel:** Gunakan class Export di `app/Exports/` yang implement `FromQuery + WithHeadings`

---

## Seeder Default

```
Admin : admin@epolling.com / admin123
User  : user@epolling.com  / user123
```

Tambahkan data dummy: minimal 6 user, 6 polling dengan opsi dan vote agar demo terlihat berisi.

---

## Catatan Teknis

- Slug polling dibuat otomatis dari judul + random string 6 karakter (cegah duplikat)
- Voter key unik per polling (bukan global) — satu orang bisa vote di polling berbeda
- Gambar disimpan di `storage/app/public/polls/` dan `storage/app/public/poll-options/`
- Jalankan `php artisan storage:link` setelah install
- Session key per polling: `voter_key_{poll_id}` — dibersihkan setelah vote berhasil

---

*Generated by Claude — untuk project e-Polling by arifsiddikm*
