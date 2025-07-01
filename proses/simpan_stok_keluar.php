<?php
require_once '../config/database.php';

$id_produk     = $_POST['id_produk'];
$id_lokasi     = $_POST['id_lokasi'];
$jumlah_keluar = $_POST['jumlah_keluar'];
$id_pelanggan  = $_POST['id_pelanggan'] ?: null; // opsional
$tipe_keluar   = $_POST['tipe_keluar'];
$nomor_ref     = $_POST['nomor_referensi'];
$keterangan    = $_POST['keterangan'];

// 1. Cek stok saat ini
$cek = $koneksi->prepare("SELECT * FROM stok_saat_ini WHERE id_produk = ? AND id_lokasi = ?");
$cek->bind_param("ii", $id_produk, $id_lokasi);
$cek->execute();
$res = $cek->get_result();

if ($res->num_rows === 0) {
    die("Stok tidak tersedia di lokasi ini.");
}

$row = $res->fetch_assoc();

if ($row['jumlah_stok'] < $jumlah_keluar) {
    die("Jumlah keluar melebihi stok tersedia.");
}

// 2. Simpan ke stok_keluar
$insert = $koneksi->prepare("INSERT INTO stok_keluar 
    (id_produk, id_lokasi, jumlah_keluar, id_pelanggan, tipe_keluar, nomor_referensi, keterangan)
    VALUES (?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param("iiissss", $id_produk, $id_lokasi, $jumlah_keluar, $id_pelanggan, $tipe_keluar, $nomor_ref, $keterangan);
$insert->execute();

// 3. Update stok_saat_ini
$update = $koneksi->prepare("UPDATE stok_saat_ini SET 
    jumlah_stok = jumlah_stok - ?, 
    tanggal_terakhir_keluar = NOW(), 
    tanggal_diperbarui = NOW()
    WHERE id_stok = ?");
$update->bind_param("ii", $jumlah_keluar, $row['id_stok']);
$update->execute();

header("Location: ../pages/stok_keluar/index.php");
