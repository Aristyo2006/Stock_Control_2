<?php
require_once '../config/database.php';

$nama     = $_POST['nama_supplier'];
$alamat   = $_POST['alamat'];
$telepon  = $_POST['telepon'];
$email    = $_POST['email'];
$kontak   = $_POST['kontak_person'];

$query = "INSERT INTO supplier (nama_supplier, alamat, telepon, email, kontak_person)
          VALUES (?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("sssss", $nama, $alamat, $telepon, $email, $kontak);

if ($stmt->execute()) {
    header("Location: ../pages/supplier/index.php");
} else {
    echo "Gagal menyimpan supplier: " . $koneksi->error;
}
