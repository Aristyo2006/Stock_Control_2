<?php
require_once '../config/database.php';

$kode      = $_POST['kode_lokasi'];
$nama      = $_POST['nama_lokasi'];
$kapasitas = $_POST['kapasitas'] ?? 0;
$deskripsi = $_POST['deskripsi'];

$query = "INSERT INTO lokasi_gudang (kode_lokasi, nama_lokasi, kapasitas, deskripsi)
          VALUES (?, ?, ?, ?)";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("ssis", $kode, $nama, $kapasitas, $deskripsi);

if ($stmt->execute()) {
    header("Location: ../pages/lokasi/index.php");
} else {
    echo "Gagal menyimpan data lokasi: " . $koneksi->error;
}
