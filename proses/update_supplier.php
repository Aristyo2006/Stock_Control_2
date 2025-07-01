<?php
require_once '../config/database.php';

$id       = $_POST['id_supplier'];
$nama     = $_POST['nama_supplier'];
$alamat   = $_POST['alamat'];
$telepon  = $_POST['telepon'];
$email    = $_POST['email'];
$kontak   = $_POST['kontak_person'];

$query = "UPDATE supplier SET 
    nama_supplier = ?, 
    alamat = ?, 
    telepon = ?, 
    email = ?, 
    kontak_person = ?
    WHERE id_supplier = ?";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("sssssi", $nama, $alamat, $telepon, $email, $kontak, $id);

if ($stmt->execute()) {
    header("Location: ../pages/supplier/index.php");
} else {
    echo "Gagal update supplier: " . $koneksi->error;
}
