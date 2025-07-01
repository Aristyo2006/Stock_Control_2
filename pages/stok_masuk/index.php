<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

// Set page title
$page_title = "Data Stok Masuk - Stock Control Gudang";

// Include the header template
require_once '../../templates/header.php';
require_once '../../config/database.php';

$query = "
SELECT sm.*, p.nama_produk, p.kode_produk, l.nama_lokasi, s.nama_supplier 
FROM stok_masuk sm
JOIN produk p ON sm.id_produk = p.id_produk
JOIN lokasi_gudang l ON sm.id_lokasi = l.id_lokasi
LEFT JOIN supplier s ON sm.id_supplier = s.id_supplier
ORDER BY sm.tanggal_masuk DESC, sm.id_stok_masuk DESC
";

$result = $koneksi->query($query);

// Get summary statistics
$stats_query = "
SELECT 
    COUNT(*) as total_entries,
    SUM(jumlah_masuk) as total_stock_in,
    COUNT(DISTINCT id_produk) as unique_products,
    COUNT(DISTINCT DATE(tanggal_masuk)) as active_days
FROM stok_masuk 
WHERE MONTH(tanggal_masuk) = MONTH(CURRENT_DATE()) 
AND YEAR(tanggal_masuk) = YEAR(CURRENT_DATE())
";
$stats_result = $koneksi->query($stats_query);
$stats = $stats_result->fetch_assoc();
?>

<style>
/* Enhanced Liquid Glass Table Styling */
.glass-container {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 24px;
    margin: 20px 0;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 
                0 2px 8px rgba(0, 0, 0, 0.05), 
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(255, 255, 255, 0.1);
    position: relative;
    overflow: hidden;
}

[data-theme="dark"] .glass-container {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 
                0 2px 8px rgba(0, 0, 0, 0.2), 
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                inset 0 -1px 0 rgba(255, 255, 255, 0.05);
}

/* Glass table styling */
.glass-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1), 
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .glass-table {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.glass-table thead {
    background: linear-gradient(135deg, rgba(103, 80, 164, 0.2) 0%, rgba(103, 80, 164, 0.1) 100%);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
}

[data-theme="dark"] .glass-table thead {
    background: linear-gradient(135deg, rgba(208, 188, 255, 0.15) 0%, rgba(208, 188, 255, 0.08) 100%);
}

.glass-table th {
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    color: var(--md-sys-color-on-surface);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.glass-table th:first-child {
    border-top-left-radius: 16px;
}

.glass-table th:last-child {
    border-top-right-radius: 16px;
}

.glass-table td {
    padding: 16px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    color: var(--md-sys-color-on-surface-variant);
    transition: all 0.3s ease;
    position: relative;
}

.glass-table tbody tr {
    transition: all 0.3s ease;
    position: relative;
}

.glass-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

[data-theme="dark"] .glass-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.05);
}

.glass-table tbody tr:last-child td:first-child {
    border-bottom-left-radius: 16px;
}

.glass-table tbody tr:last-child td:last-child {
    border-bottom-right-radius: 16px;
}

.glass-table tbody tr:last-child td {
    border-bottom: none;
}

/* Enhanced Navigation Links as Buttons */
.nav-links {
    background: none;
    border: none;
    padding: 0;
    margin-bottom: 24px;
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: center;
}

.nav-links .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 20px;
    border-radius: var(--md-sys-shape-corner-full);
    font-family: "Roboto", sans-serif;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    gap: 8px;
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1), 
                0 2px 8px rgba(0, 0, 0, 0.05), 
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

.nav-links .btn-secondary {
    background: linear-gradient(135deg, rgba(98, 91, 113, 0.15) 0%, rgba(98, 91, 113, 0.08) 100%);
    color: var(--md-sys-color-on-secondary-container);
}

.nav-links .btn-primary {
    background: linear-gradient(135deg, var(--md-sys-color-primary) 0%, rgba(103, 80, 164, 0.9) 100%);
    color: var(--md-sys-color-on-primary);
}

.nav-links .btn:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), 
                0 4px 12px rgba(0, 0, 0, 0.1), 
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
}

.nav-links .btn-secondary:hover {
    background: linear-gradient(135deg, rgba(98, 91, 113, 0.25) 0%, rgba(98, 91, 113, 0.15) 100%);
}

.nav-links .btn-primary:hover {
    background: linear-gradient(135deg, rgba(103, 80, 164, 1) 0%, rgba(103, 80, 164, 0.95) 100%);
}

/* Statistics cards */
.stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 16px;
    text-align: center;
}

[data-theme="dark"] .stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: var(--md-sys-color-primary);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: var(--md-sys-color-on-surface-variant);
    font-weight: 500;
}

/* Reference number styling */
.reference-number {
    font-family: 'Courier New', monospace;
    background: rgba(103, 80, 164, 0.1);
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    color: var(--md-sys-color-primary);
    border: 1px solid rgba(103, 80, 164, 0.2);
}

/* Responsive design */
@media (max-width: 768px) {
    .glass-container {
        padding: 16px;
        margin: 16px 0;
        border-radius: 16px;
    }
    
    .glass-table {
        font-size: 14px;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    .glass-table th,
    .glass-table td {
        padding: 12px 8px;
    }
    
    .nav-links {
        flex-direction: column;
        align-items: stretch;
    }
    
    .nav-links .btn {
        width: 100%;
        justify-content: center;
    }
    
    .stats-overview {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .stat-number {
        font-size: 20px;
    }
}

/* Add floating particles effect */
.glass-container .particle {
    position: absolute;
    width: 2px;
    height: 2px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 50%;
    animation: particle-float 8s linear infinite;
    pointer-events: none;
}

@keyframes particle-float {
    0% {
        transform: translateY(100px) translateX(0px);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-100px) translateX(20px);
        opacity: 0;
    }
}
</style>

<div class="container">
    <h2>📦 Data Stok Masuk</h2>
    
    <!-- Statistics Overview -->
    <div class="stats-overview">
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['total_entries']); ?></div>
            <div class="stat-label">📝 Total Entri Bulan Ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['total_stock_in']); ?></div>
            <div class="stat-label">📦 Total Stok Masuk</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['unique_products']); ?></div>
            <div class="stat-label">🏷️ Produk Berbeda</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['active_days']); ?></div>
            <div class="stat-label">📅 Hari Aktif</div>
        </div>
    </div>
    
    <div class="nav-links">
        <a href="../../pages/dashboard.php" class="btn btn-secondary">
            ← Kembali ke Dashboard
        </a>
        <a href="tambah.php" class="btn btn-primary">
            + Tambah Stok Masuk
        </a>
    </div>

    <div class="glass-container">
        <table class="glass-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Lokasi</th>
                    <th>Jumlah Masuk</th>
                    <th>Supplier</th>
                    <th>Referensi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal_masuk'])); ?></td>
                            <td><strong><?= htmlspecialchars($row['kode_produk']); ?></strong></td>
                            <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                            <td><?= htmlspecialchars($row['nama_lokasi']); ?></td>
                            <td><strong><?= number_format($row['jumlah_masuk']); ?> unit</strong></td>
                            <td><?= htmlspecialchars($row['nama_supplier']) ?: '-'; ?></td>
                            <td>
                                <span class="reference-number">
                                    <?= htmlspecialchars($row['nomor_referensi']); ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['keterangan']) ?: '-'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px; color: var(--md-sys-color-on-surface-variant);">
                            📦 Belum ada data stok masuk
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    console.log('📦 Stok Masuk page loaded');
    
    // Add floating particles to glass container
    const glassContainer = document.querySelector('.glass-container');
    if (glassContainer) {
        createFloatingParticles(glassContainer);
    }
    
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.glass-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01) translateZ(10px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) translateZ(0px)';
        });
    });
    
    // Add click-to-copy functionality for reference numbers
    const referenceNumbers = document.querySelectorAll('.reference-number');
    referenceNumbers.forEach(ref => {
        ref.addEventListener('click', function() {
            const text = this.textContent;
            navigator.clipboard.writeText(text).then(() => {
                // Show temporary feedback
                const originalText = this.textContent;
                this.textContent = '✓ Copied!';
                this.style.background = 'rgba(76, 175, 80, 0.2)';
                this.style.color = '#4caf50';
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.background = '';
                    this.style.color = '';
                }, 1500);
            }).catch(() => {
                console.log('Copy failed');
            });
        });
        
        // Add hover effect
        ref.style.cursor = 'pointer';
        ref.title = 'Klik untuk menyalin nomor referensi';
    });
    
    // Add animation to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.style.animation = 'slideInUp 0.6s ease forwards';
    });
});

function createFloatingParticles(container) {
    // Create subtle floating particles for premium effect
    for (let i = 0; i < 4; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = 8 + Math.random() * 4 + 's';
        container.appendChild(particle);
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>

<?php
// Include the footer template
require_once '../../templates/footer.php';
?>
