# Project Status: YouLibrary

## Overview
YouLibrary is a complete Library Management System built with Laravel, featuring role-based authentication (Admin, Staff, Reader), book management, borrowing machinery, and a polished frontend.

## Features Implemented

### 1. Authentication & Roles
- **System**: Laravel Breeze.
- **Roles**: Admin, Staff, Reader.
- **Middleware**: Custom `CheckRole` middleware for route protection.

### 2. Admin Panel (`/admin`)
- **Dashboard**: Real-time statistics for Books, Users, Active Borrows, and **Overdue Books**.
- **User Management**:
  - List Readers and Staff.
  - Promote Readers to Staff.
  - Delete Users.
- **Book Management**:
  - Complete CRUD (Create, Read, Update, Delete).
  - Image Upload for Book Covers.
- **Borrow Management**:
  - View active borrows.
  - Mark books as "Returned" (automatically restores stock).
- **Reports**:
  - Dedicated page for transaction logs.
  - Print/PDF friendly layout.

### 3. Reader Dashboard (`/dashboard`)
- **Browse Books**: Grid view with cover images and stock status.
- **Search**: Real-time filtering by Title, Author, or Genre.
- **Borrowing**:
  - "Pinjam Buku" button with stock validation.
  - **Digital Receipt**: SweetAlert popup showing borrow details.
- **My Library**:
  - **History**: View current and past loans.
  - **Bookmarks**: Save books for later.

### 4. Technical Details
- **Database**: MySQL (Table structure provided in migrations).
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js.
- **Alerts**: SweetAlert2 for user feedback.

## Setup Instructions
1. **Prerequisites**: PHP, Composer, Node.js, MySQL (Laragon/XAMPP).
2. **Installation**:
   ```bash
   composer install
   npm install && npm run build
   php artisan migrate:fresh --seed
   ```
3. **Default Accounts**:
   - Admin: `admin@youlibrary.com` / `password`
   - Staff: `staff@youlibrary.com` / `password`
   - Reader: `reader@youlibrary.com` / `password`

## Recent Updates
- Added **Overdue Logic** in Admin Dashboard.
- Added **Search Functionality** in Reader Dashboard.
- Custom **Landing Page**.

**Status**: 🟢 Completed
