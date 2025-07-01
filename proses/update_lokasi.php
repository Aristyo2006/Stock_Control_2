<?php
require_once '../config/database.php';

$id        = $_POST['id_lokasi'];
$kode      = $_POST['kode_lokasi'];
$nama      = $_POST['nama_lokasi'];
$kapasitas = $_POST['kapasitas'] ?? 0;
$deskripsi = $_POST['deskripsi'];

$query = "UPDATE lokasi_gudang SET 
    kode_lokasi = ?, 
    nama_lokasi = ?, 
    kapasitas = ?, 
    deskripsi = ?
    WHERE id_lokasi = ?";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("ssisi", $kode, $nama, $kapasitas, $deskripsi, $id);

if ($stmt->execute()) {
    header("Location: ../pages/lokasi/index.php");
} else {
    echo "Gagal update lokasi: " . $koneksi->error;
}
