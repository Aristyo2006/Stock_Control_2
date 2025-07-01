<?php
// Konfigurasi database
$host     = "localhost";       // Host database
$user     = "root";            // Username database (default XAMPP: root)
$password = "";                // Password database (default XAMPP: kosong)
$database = "db_stock_gudang"; // Nama database

// Membuat koneksi
$koneksi = new mysqli($host, $user, $password, $database);
// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
// Jika berhasil, koneksi disiapkan untuk digunakan
?>
