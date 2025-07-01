<?php
require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_POST) {
    $id_produk = $_POST['id_produk'] ?? 0;
    $id_lokasi = $_POST['id_lokasi'] ?? 0;
    $jumlah_keluar = $_POST['jumlah_keluar'] ?? 0;
    
    // Get current stock
    $query = "SELECT ss.*, p.nama_produk, l.nama_lokasi 
              FROM stok_saat_ini ss
              JOIN produk p ON ss.id_produk = p.id_produk
              JOIN lokasi_gudang l ON ss.id_lokasi = l.id_lokasi
              WHERE ss.id_produk = ? AND ss.id_lokasi = ?";
    
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ii", $id_produk, $id_lokasi);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stock = $result->fetch_assoc();
        $available_stock = $stock['jumlah_stok'];
        $sufficient = $available_stock >= $jumlah_keluar;
        
        echo json_encode([
            'success' => true,
            'sufficient' => $sufficient,
            'available_stock' => $available_stock,
            'requested_stock' => $jumlah_keluar,
            'product_name' => $stock['nama_produk'],
            'location_name' => $stock['nama_lokasi'],
            'message' => $sufficient ? 'Stok mencukupi' : 'Jumlah keluar melebihi stok tersedia.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'sufficient' => false,
            'available_stock' => 0,
            'requested_stock' => $jumlah_keluar,
            'message' => 'Stok tidak tersedia di lokasi ini.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
