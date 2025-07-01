<?php
require_once '../config/database.php';

$id_produk     = $_POST['id_produk'];
$id_lokasi     = $_POST['id_lokasi'];
$jumlah_masuk  = $_POST['jumlah_masuk'];
$id_supplier   = $_POST['id_supplier'] ?: null; // optional
$nomor_ref     = $_POST['nomor_referensi'];
$keterangan    = $_POST['keterangan'];

// 1. Simpan ke stok_masuk
$query = "INSERT INTO stok_masuk (id_produk, id_lokasi, jumlah_masuk, id_supplier, nomor_referensi, keterangan)
          VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("iiisss", $id_produk, $id_lokasi, $jumlah_masuk, $id_supplier, $nomor_ref, $keterangan);
$stmt->execute();

// 2. Cek apakah stok_saat_ini untuk produk & lokasi ini sudah ada
$cek = $koneksi->prepare("SELECT * FROM stok_saat_ini WHERE id_produk = ? AND id_lokasi = ?");
$cek->bind_param("ii", $id_produk, $id_lokasi);
$cek->execute();
$res = $cek->get_result();

if ($res->num_rows > 0) {
    // Jika ada, update jumlah_stok + tanggal
    $row = $res->fetch_assoc();
    $update = $koneksi->prepare("
        UPDATE stok_saat_ini SET 
            jumlah_stok = jumlah_stok + ?, 
            tanggal_terakhir_masuk = NOW(), 
            tanggal_diperbarui = NOW() 
        WHERE id_stok = ?");
    $update->bind_param("ii", $jumlah_masuk, $row['id_stok']);
    $update->execute();
} else {
    // Jika tidak ada, insert baru ke stok_saat_ini
    $insert = $koneksi->prepare("
        INSERT INTO stok_saat_ini (id_produk, id_lokasi, jumlah_stok, tanggal_terakhir_masuk)
        VALUES (?, ?, ?, NOW())");
    $insert->bind_param("iii", $id_produk, $id_lokasi, $jumlah_masuk);
    $insert->execute();
}

header("Location: ../pages/stok_masuk/index.php");
