<p align="center">
  <img src="public/image/OULIBRARY.png" alt="YouLibrary Logo" width="300">
</p>

<h1 align="center">YouLibrary - Sistem Manajemen Perpustakaan</h1>

<p align="center">
  <strong>Aplikasi perpustakaan digital modern dengan desain B&W Grid minimalis</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📖 Tentang Aplikasi

**YouLibrary** adalah aplikasi manajemen perpustakaan berbasis web yang dibangun menggunakan Laravel. Aplikasi ini memiliki desain **Neo-Brutalism B&W Grid** yang modern dan minimalis dengan fitur lengkap untuk mengelola koleksi buku dan peminjaman.

### ✨ Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| 📚 **Manajemen Buku** | CRUD lengkap untuk koleksi buku dengan cover upload |
| 👥 **Multi-Role System** | Admin dan Reader dengan akses berbeda |
| 📋 **Peminjaman Buku** | Form konfirmasi dengan nama, alamat, dan durasi pinjam |
| 🔖 **Bookmark** | Simpan buku favorit untuk dipinjam nanti |
| 📊 **Dashboard Admin** | Statistik dan recent borrows real-time |
| 📝 **Riwayat Peminjaman** | Tracking status peminjaman (Active, Returned, Overdue) |
| 🎫 **Struk Digital** | Receipt otomatis setelah peminjaman berhasil |

---

## 🎨 Design Philosophy

Aplikasi ini menggunakan konsep **Neo-Brutalism / B&W Grid Design**:

- ⬛ **Sharp corners** - Tidak ada rounded corners
- 🖤 **Black borders** - Semua elemen dibatasi garis hitam tegas
- 📐 **Grid shadows** - Shadow dengan offset seperti `shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]`
- ❤️ **Red accent** - Warna brand `#FF3B30` untuk call-to-action
- ⬜ **White background** - High contrast untuk accessibility

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL 8.x
- **Authentication**: Laravel Breeze
- **JavaScript**: Vanilla JS (No framework dependency)
- **Alerts**: SweetAlert2

---

## 🚀 Instalasi

```bash
# Clone repository
git clone https://github.com/rubysy/app-management-library.git
cd app-management-library

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Build assets
npm run build

# Start server
php artisan serve
```

---

## 👤 Default Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@library.co | password |
| Reader | reader@library.co | password |

---

## 📁 Struktur Folder

```
app/
├── Http/Controllers/     # Controllers (Admin, Borrow, Bookmark)
├── Models/               # Eloquent Models (User, Book, Borrow, Bookmark)
resources/views/
├── admin/                # Admin dashboard & management pages
├── reader/               # Reader pages (bookmarks, history)
├── components/           # Blade components (buttons, inputs)
├── layouts/              # Layout templates (admin, reader, guest)
database/
├── migrations/           # Database schema
├── seeders/              # Sample data
```

---

## 📜 License

This project is open-sourced software for educational purposes (UKK SMK).

---

<p align="center">
  Made with ❤️ by <strong>rubysy</strong>
</p>
