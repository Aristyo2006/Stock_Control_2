<?php
require_once '../config/database.php';

$id         = $_POST['id_produk'];
$kode       = $_POST['kode_produk'];
$nama       = $_POST['nama_produk'];
$deskripsi  = $_POST['deskripsi'];
$satuan     = $_POST['satuan'];
$harga_beli = $_POST['harga_beli'];
$harga_jual = $_POST['harga_jual'];
$stok_min   = $_POST['stok_minimal'] ?? 0;

$query = "UPDATE produk SET 
    kode_produk = ?, 
    nama_produk = ?, 
    deskripsi = ?, 
    satuan = ?, 
    harga_beli = ?, 
    harga_jual = ?, 
    stok_minimal = ?
    WHERE id_produk = ?";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("ssssddii", $kode, $nama, $deskripsi, $satuan, $harga_beli, $harga_jual, $stok_min, $id);

if ($stmt->execute()) {
    header("Location: ../pages/produk/index.php");
} else {
    echo "Gagal update data: " . $koneksi->error;
}
