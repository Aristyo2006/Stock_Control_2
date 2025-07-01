<?php
require_once '../config/database.php';

$kode       = $_POST['kode_produk'];
$nama       = $_POST['nama_produk'];
$deskripsi  = $_POST['deskripsi'];
$satuan     = $_POST['satuan'];
$harga_beli = $_POST['harga_beli'];
$harga_jual = $_POST['harga_jual'];
$stok_min   = $_POST['stok_minimal'] ?? 0;

$query = "INSERT INTO produk 
    (kode_produk, nama_produk, deskripsi, satuan, harga_beli, harga_jual, stok_minimal)
    VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("ssssddi", $kode, $nama, $deskripsi, $satuan, $harga_beli, $harga_jual, $stok_min);

if ($stmt->execute()) {
    header("Location: ../pages/produk/index.php");
} else {
    echo "Gagal menyimpan data: " . $koneksi->error;
}
