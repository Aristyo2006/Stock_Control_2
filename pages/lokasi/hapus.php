<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}
require_once '../../config/database.php';

$id = $_GET['id'];

$query = "DELETE FROM lokasi_gudang WHERE id_lokasi = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Gagal menghapus lokasi: " . $koneksi->error;
}
