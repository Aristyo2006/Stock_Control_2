<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}
require_once '../../config/database.php';

$id = $_GET['id'];
$query = "DELETE FROM produk WHERE id_produk = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Gagal menghapus data: " . $koneksi->error;
}
