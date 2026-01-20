# Book Management App (Technical Test)

Aplikasi manajemen buku sederhana berbasis **Laravel 12** yang mencakup Backend REST API dan Frontend Single Page Application (SPA) menggunakan **Tailwind CSS** (CDN).

Proyek ini dibuat untuk memenuhi persyaratan _Technical Test_ dengan fitur CRUD (Create, Read, Update, Delete) dan Pencarian.

## 🌐 Live Demo

Anda dapat melihat demo aplikasi yang sudah berjalan di sini:
👉 **[https://crudbook.1cloud.my.id](https://crudbook.1cloud.my.id)**

---

## 📋 Persyaratan Sistem

Pastikan komputer Anda telah terinstal:

- PHP >= 8.2
- Composer
- MySQL

## 🚀 Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal Anda:

### 1. Clone atau Download Repository

Jika Anda menggunakan git:

```bash
git clone <repository_url>
cd book-app

```

### 2. Instalasi Dependensi

Jalankan perintah berikut untuk mengunduh pustaka Laravel:

```bash
composer install

```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env

```

Buka file `.env` dan sesuaikan konfigurasi database Anda:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=

```

### 4. Generate Application Key

```bash
php artisan key:generate

```

### 5. Setup API (Laravel 11/12)

Jika belum terinstall, jalankan perintah ini untuk mengaktifkan fitur API:

```bash
php artisan install:api

```

### 6. Migrasi Database

Buat tabel di database:

```bash
php artisan migrate

```

### 7. Jalankan Aplikasi

```bash
php artisan serve

```

Akses aplikasi melalui browser di: `http://localhost:8000`

---

## 📚 Dokumentasi API

Berikut adalah daftar endpoint API yang tersedia.

### 1. List Books (Daftar Buku)

Mengambil daftar buku dengan pagination (4 buku per halaman) dan fitur pencarian.

- **URL**: `/api/books`
- **Method**: `GET`
- **Query Params**:
- `page`: Nomor halaman (default: 1)
- `search`: Keyword pencarian berdasarkan judul atau deskripsi (opsional)

### 2. Create Book (Tambah Buku)

Menambahkan data buku baru.

- **URL**: `/api/books`
- **Method**: `POST`
- **Body (JSON)**:

```json
{
    "book_name": "Judul Buku",
    "description": "Deskripsi singkat...",
    "author": "Nama Penulis",
    "published_date": "2024-01-01"
}
```

- **Validasi**:
- `book_name`: Wajib, Max 150 char, Unik jika dikombinasikan dengan author.
- `author`: Wajib, Max 150 char.
- `published_date`: Wajib, Format tanggal valid.

### 3. Update Book (Update Deskripsi)

Memperbarui deskripsi buku. Sesuai ketentuan soal, hanya deskripsi yang dapat diubah.

- **URL**: `/api/books/{id}`
- **Method**: `PUT`
- **Body (JSON)**:

```json
{
    "description": "Deskripsi baru yang telah diperbarui."
}
```

### 4. Delete Book (Hapus Buku)

Menghapus buku berdasarkan ID.

- **URL**: `/api/books/{id}`
- **Method**: `DELETE`

---

## 🛠 Struktur Proyek

- **Model**: `app/Models/Book.php`
- **Controller**: `app/Http/Controllers/BookController.php`
- **Migration**: `database/migrations/xxxx_xx_xx_xxxxxx_create_books_table.php`
- **Frontend View**: `resources/views/books/index.blade.php`

## ✨ Catatan Tambahan

Frontend dibuat menggunakan HTML5 dan JavaScript murni (Vanilla JS) dengan styling Tailwind CSS via CDN, sehingga tidak memerlukan build step (npm run dev/build) untuk tampilan.
