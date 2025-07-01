<?php
require_once '../config/database.php';

$id      = $_POST['id_pelanggan'];
$nama    = $_POST['nama_pelanggan'];
$alamat  = $_POST['alamat'];
$telepon = $_POST['telepon'];
$email   = $_POST['email'];

$query = "UPDATE pelanggan SET 
    nama_pelanggan = ?, 
    alamat = ?, 
    telepon = ?, 
    email = ?
    WHERE id_pelanggan = ?";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("ssssi", $nama, $alamat, $telepon, $email, $id);

if ($stmt->execute()) {
    header("Location: ../pages/pelanggan/index.php");
} else {
    echo "Gagal update pelanggan: " . $koneksi->error;
}
