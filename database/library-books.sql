
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS users;

-- 1. Tabel Users (Untuk Login & Register)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabel Books (Untuk Menyimpan Buku per User)
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- Penanda pemilik buku
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    status ENUM('ingin_dibaca', 'sedang_dibaca', 'sudah_dibaca') DEFAULT 'ingin_dibaca',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);