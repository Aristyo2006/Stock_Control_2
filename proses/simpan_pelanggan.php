<?php
require_once '../config/database.php';

$nama    = $_POST['nama_pelanggan'];
$alamat  = $_POST['alamat'];
$telepon = $_POST['telepon'];
$email   = $_POST['email'];

$query = "INSERT INTO pelanggan (nama_pelanggan, alamat, telepon, email)
          VALUES (?, ?, ?, ?)";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("ssss", $nama, $alamat, $telepon, $email);

if ($stmt->execute()) {
    header("Location: ../pages/pelanggan/index.php");
} else {
    echo "Gagal menyimpan pelanggan: " . $koneksi->error;
}
