<?php
$conn = new mysqli("localhost", "root", "", "xss_advanced");
$result = $conn->query("SELECT * FROM comments");

while ($row = $result->fetch_assoc()) {
    echo "<b>" . $row['username'] . "</b>: " . $row['comment'] . "<br><hr>";
}
?>
