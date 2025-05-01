<?php
$conn = new mysqli("localhost", "root", "", "xss_advanced");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
