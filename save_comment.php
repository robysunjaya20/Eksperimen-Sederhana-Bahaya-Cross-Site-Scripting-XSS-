<?php
$conn = new mysqli("localhost", "root", "", "xss_advanced");

$username = $_POST['username'];
$comment = $_POST['comment'];

$conn->query("INSERT INTO comments (username, comment) VALUES ('$username', '$comment')");
echo "Komentar berhasil disimpan.<br>";
echo "<a href='view_comments.php'>Lihat Komentar</a>";
?>
