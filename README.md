# 📚 History Book - Modular PHP Library App

Aplikasi Perpustakaan Sederhana berbasis Web menggunakan PHP Native (Modular) dan Vue.js. Aplikasi ini digunakan untuk mencatat riwayat buku yang ingin, sedang, dan sudah dibaca.

---

## 🛠️ Persiapan Awal (Prerequisites)

Sebelum menjalankan program, pastikan Anda telah menyiapkan hal-hal berikut:

1.  **Download Source Code & SQL**: Pastikan Anda memiliki semua file project dan file database (`database.sql`).
2.  **Install XAMPP**: Pastikan XAMPP sudah terinstall di komputer Anda.
3.  **Nyalakan Server**: Buka XAMPP Control Panel, lalu klik **Start** pada modul **Apache** dan **MySQL**.

---

## 🗄️ Instalasi Database

Agar aplikasi dapat berjalan secara offline (lokal), Anda perlu memasang database terlebih dahulu:

1.  Buka browser dan akses **[http://localhost/phpmyadmin](http://localhost/phpmyadmin)**.
2.  Buat database baru dengan nama: `db_library` (atau sesuaikan dengan konfigurasi di `koneksi.php`).
3.  Pilih database yang baru dibuat, lalu klik tab **Import**.
4.  Klik **Choose File**, pilih file `.sql` yang sudah didownload.
5.  Klik tombol **Go** / **Kirim** di bagian bawah.

> **Catatan:** Jika Anda belum memiliki file SQL, Anda bisa menyalin query SQL yang ada di bagian bawah dokumen ini.

---

## 🚀 Menjalankan Aplikasi

1.  Pindahkan folder project ini ke dalam folder `htdocs` di instalasi XAMPP Anda (biasanya di `C:\xampp\htdocs\nama_folder_project`).
2.  Buka browser (Chrome, Firefox, atau Edge).
3.  Ketik alamat berikut di address bar:

    ```
    http://localhost/nama_folder_project
    ```
    *(Ganti `nama_folder_project` dengan nama folder yang Anda buat di htdocs)*.

---

## 📖 Panduan Penggunaan

Berikut adalah alur penggunaan aplikasi:

1.  **Registrasi Akun**: 
    * Saat pertama kali membuka aplikasi, Anda akan diarahkan ke halaman Login.
    * Jika belum memiliki akun pada data SQL, klik tombol/link **"Daftar"** terlebih dahulu.
    * Isi username dan password untuk membuat akun baru.

2.  **Login**:
    * Masuk menggunakan username dan password yang telah didaftarkan.
    * Setelah berhasil, Anda akan masuk ke Dashboard Library.

3.  **Menambah Buku (Input)**:
    * Pada panel sebelah kiri, isi **Judul Buku** dan **Penulis**.
    * Pilih status awal (misalnya: *Ingin Dibaca*).
    * Klik **Simpan**. Buku akan muncul di daftar.

4.  **Mengupdate Status Buku**:
    * Lihat daftar buku di sebelah kanan.
    * Anda bisa memindahkan buku ke tahapan selanjutnya (misal: dari *Ingin Dibaca* ➡️ *Sedang Dibaca* ➡️ *Sudah Dibaca*) dengan mengklik tombol status yang tersedia di tiap buku.

5.  **Menghapus Buku**:
    * Jika ingin menghapus data, klik ikon **Sampah** (Delete) pada buku yang diinginkan.
    * Buku akan terhapus permanen dari database.

---

## 📂 Struktur Database (Lampiran)

Jika Anda membutuhkan query SQL manual, gunakan kode berikut di tab "SQL" pada phpMyAdmin:

```sql
CREATE DATABASE IF NOT EXISTS db_perpustakaan;
USE db_perpustakaan;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    author VARCHAR(100) NOT NULL,
    status ENUM('ingin_dibaca', 'sedang_dibaca', 'sudah_dibaca') DEFAULT 'ingin_dibaca',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

