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
