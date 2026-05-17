# e-Polling — Platform Polling Online

Platform polling publik berbasis web. User bisa membuat polling bebas yang dapat diisi oleh siapa saja melalui link unik, tanpa perlu login.

🌐 **Live Demo:** [demo-epolling.arifsiddikm.com](https://demo-epolling.arifsiddikm.com)

---

## Tech Stack

- **Backend:** PHP 8.3 + Laravel 12
- **Database:** SQLite / MySQL
- **Frontend:** Tailwind CSS · Alpine.js · SweetAlert2
- **Export:** Laravel Excel (PhpSpreadsheet) · DomPDF
- **Font:** Inter (Google Fonts)

---

## Fitur

**Publik (tanpa login)**
- Akses polling via link unik (`/p/{slug}`)
- Input voter key (NIM / NIK / ID / Email, kustom per polling)
- Cegah double vote berdasarkan voter key
- Lihat hasil polling secara real-time

**User Panel** (`/dashboard`)
- Register & login akun
- Buat, edit, hapus polling sendiri
- Tambah opsi dengan gambar & deskripsi
- Toggle aktif/nonaktif polling
- Atur tanggal mulai & selesai
- Rekap hasil lengkap per opsi
- Export hasil ke PDF & Excel

**Admin Panel** (`/webmin`)
- Dashboard statistik global (total user, polling, suara)
- Kelola semua user (nonaktifkan, hapus)
- Kelola semua polling (toggle status, hapus)
- Export data polling & user (PDF & Excel)
- Kelola akun admin

---

## Instalasi

```bash
# 1. Clone repo
git clone https://github.com/arifsiddikm/epolling.git
cd epolling

# 2. Install dependencies
composer install

# 3. Copy dan konfigurasi .env
cp file env to .env and setting your password
php artisan key:generate

# 4. Setup database (SQLite default)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# 5. Storage link
php artisan storage:link

# 6. Build frontend (opsional, sudah ada build)
npm install && npm run build

# 7. Jalankan server
php artisan serve
```

Akses di `http://localhost:8000`

---

## Login

**Admin**
```
URL   : http://localhost:8000/webmin
Email : admin@epolling.com
Pass  : admin123
```

**User Demo**
```
URL   : http://localhost:8000/login
Email : user@epolling.com
Pass  : user123
```

---

## Konfigurasi MySQL (opsional)

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=epolling
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan ulang:
```bash
php artisan migrate
php artisan db:seed
```

---

### Support me on
<a href="https://saweria.co/arifsiddikm" target="_blank"><img src="https://user-images.githubusercontent.com/26188697/180601310-e82c63e4-412b-4c36-b7b5-7ba713c80380.png" alt="Sawer me" height="41" width="174"></a>
