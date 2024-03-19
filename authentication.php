<?php
session_start();

$valid_username = "PROPTOKART";
$valid_password = "Proptokart123@";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === $valid_username && $password === $valid_password) {

    $_SESSION['authenticated'] = true;
    header("Location: list.php");
    exit;
} else {
    $_SESSION['error'] = "Incorrect username or password";
    echo "<script>alert('Incorrect username or password'); window.location.href = 'login_form.php';</script>";
    exit;
}
?>
