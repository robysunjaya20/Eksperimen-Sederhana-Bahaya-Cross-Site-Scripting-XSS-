# 🔒 Eksperimen Keamanan Web: Cross-Site Scripting (XSS)

Eksperimen ini mendemonstrasikan dua jenis serangan XSS (Reflected dan Stored) secara lokal menggunakan XAMPP dan Node.js. Tujuannya adalah untuk memahami cara kerja XSS dan cara mitigasinya.

---

## 🧰 Persiapan Lingkungan

### ✅ Alat yang Dibutuhkan
- [XAMPP](https://www.apachefriends.org/index.html) (Apache & MySQL)
- [Node.js](https://nodejs.org)
- Web browser (Chrome/Firefox)
- Text editor (VS Code/Notepad++)

---

## 📁 Struktur Direktori
xss_advanced/

		├── comment_form.php
		├── save_comment.php
		├── view_comments.php
		├── dashboard.php
		├── login.php
		├── logout.php
		├── db.php
		├── style.css
		└── xss_listener/
		└── server.js

---

## ⚙️ Setup Database MySQL

### 1. Buat Database Baru
Masuk ke `http://localhost/phpmyadmin`, buat database baru bernama `xss_advanced`.

### 2. Jalankan SQL Berikut:
```sql
CREATE TABLE `comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50),
  `comment` TEXT
);

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50),
  `password` VARCHAR(255)
);

INSERT INTO users (username, password) VALUES ('admin', '1234');

---

## 🧱 Langkah-Langkah
### 1. Buat Login.php

