<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}
require_once '../../config/database.php';

$id = $_GET['id'];

$query = "DELETE FROM supplier WHERE id_supplier = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Gagal menghapus supplier: " . $koneksi->error;
}
