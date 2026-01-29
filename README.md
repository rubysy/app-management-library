<p align="center">
  <img src="public/image/OULIBRARY.png" alt="YouLibrary Logo" width="300">
</p>

<h1 align="center">YouLibrary - Sistem Manajemen Perpustakaan</h1>

<p align="center">
  <strong>Aplikasi perpustakaan digital modern dengan desain B&W Grid yang keren dan minimalis 📚</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📖 Tentang Aplikasi

**YouLibrary** adalah aplikasi manajemen perpustakaan berbasis web yang dibuat dengan Laravel. Aplikasi ini punya desain **Neo-Brutalism B&W Grid** yang modern banget dengan fitur lengkap buat kelola koleksi buku dan peminjaman.

### ✨ Fitur-Fitur Keren

| Fitur | Keterangan |
|-------|-----------|
| 📚 **Kelola Buku** | Tambah, edit, hapus buku lengkap dengan upload cover |
| 👥 **Sistem Role** | Ada Admin dan Reader dengan akses yang beda |
| 📋 **Pinjam Buku** | Isi form konfirmasi (nama, alamat, durasi pinjam) |
| 🔖 **Bookmark** | Simpan buku favorit buat dipinjam nanti |
| 📊 **Dashboard Admin** | Lihat statistik dan peminjaman terbaru |
| 📝 **Riwayat Pinjam** | Tracking status (Aktif, Dikembalikan, Terlambat) |
| 🎫 **Struk Digital** | Dapet struk otomatis setelah pinjam buku |

---

## 🎨 Konsep Desain

Aplikasi ini pakai konsep **Neo-Brutalism / B&W Grid Design** yang lagi trend:

- ⬛ **Sudut tajam** - Gak pake sudut melengkung sama sekali
- 🖤 **Border hitam** - Semua elemen punya garis hitam tegas
- 📐 **Shadow kotak** - Shadow unik kayak `shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]`
- ❤️ **Aksen merah** - Warna brand `#FF3B30` buat tombol penting
- ⬜ **Background putih** - Kontras tinggi biar gampang dibaca

---

## 🛠️ Teknologi yang Dipakai

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL 8.x
- **Autentikasi**: Laravel Breeze
- **JavaScript**: Vanilla JS (tanpa framework berat)
- **Notifikasi**: SweetAlert2

---

## 🚀 Cara Install

```bash
# Clone repo ini
git clone https://github.com/rubysy/app-management-library.git
cd app-management-library

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Buat database & isi data dummy
php artisan migrate --seed

# Build assets
npm run build

# Jalanin server
php artisan serve
```

Buka browser dan akses: `http://localhost:8000`

---

## 👤 Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@library.co | password |
| Reader | reader@library.co | password |

---

## 📁 Struktur Folder

```
app/
├── Http/Controllers/     # Controllers (Admin, Borrow, Bookmark)
├── Models/               # Model Eloquent (User, Book, Borrow, Bookmark)
resources/views/
├── admin/                # Halaman admin & manajemen
├── reader/               # Halaman reader (bookmark, riwayat)
├── components/           # Komponen Blade (button, input)
├── layouts/              # Template layout (admin, reader, guest)
database/
├── migrations/           # Skema database
├── seeders/              # Data contoh
```

---

## 🎓 Info Project

Project ini dibuat untuk **Uji Kompetensi Keahlian (UKK)** SMK dengan fokus pada:
- Pengembangan aplikasi web dengan framework Laravel
- Implementasi sistem autentikasi dan otorisasi
- Desain UI/UX modern dengan Tailwind CSS
- Manajemen database relasional

---

## 📜 Lisensi

Project ini open-source untuk keperluan edukasi.

---

<p align="center">
  Dibuat dengan ❤️ oleh <strong>rubysy</strong>
</p>
