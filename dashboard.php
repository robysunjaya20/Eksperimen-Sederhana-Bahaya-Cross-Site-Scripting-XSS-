<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
echo "Selamat datang, " . $_SESSION['username'] . "<br>";
echo "<a href='comment_form.php'>Komentar</a>";
