<?php
session_start();
require_once '../config/database.php';

// Dummy user, nanti bisa diganti dari tabel user
$valid_username = "admin";
$valid_password = "12345"; // plaintext, nanti bisa dienkripsi

$username = $_POST['username'];
$password = $_POST['password'];

if ($username === $valid_username && $password === $valid_password) {
    $_SESSION['username'] = $username;
    header("Location: ../pages/dashboard.php");
} else {
    header("Location: ../login.php?error=Login gagal. Username atau password salah.");
}
