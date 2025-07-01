<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

// Set page title
$page_title = "Stok Saat Ini - Stock Control Gudang";

// Include the header template
require_once '../../templates/header.php';
require_once '../../config/database.php';

$query = "
SELECT ssi.*, p.nama_produk, p.kode_produk, p.stok_minimal, l.nama_lokasi 
FROM stok_saat_ini ssi
JOIN produk p ON ssi.id_produk = p.id_produk
JOIN lokasi_gudang l ON ssi.id_lokasi = l.id_lokasi
ORDER BY p.nama_produk ASC, l.nama_lokasi ASC
";

$result = $koneksi->query($query);

// Get summary statistics
$stats_query = "
SELECT 
    COUNT(*) as total_items,
    SUM(jumlah_stok) as total_stock,
    COUNT(CASE WHEN jumlah_stok = 0 THEN 1 END) as empty_stock,
    COUNT(CASE WHEN jumlah_stok <= 10 AND jumlah_stok > 0 THEN 1 END) as low_stock,
    COUNT(CASE WHEN jumlah_stok > 10 THEN 1 END) as good_stock
FROM stok_saat_ini ssi
JOIN produk p ON ssi.id_produk = p.id_produk
";
$stats_result = $koneksi->query($stats_query);
$stats = $stats_result->fetch_assoc();
?>

<!-- COMPREHENSIVE: All Interactive Elements for Stok Saat Ini -->
<script>
console.log('🔧 Stok Saat Ini: Loading ALL interactive elements...');

// Wait for DOM and force initialize ALL components
function initializeAllComponents() {
    console.log('🚀 Stok Saat Ini: Initializing ALL components...');
    
    // 1. MAIN NAVIGATION
    initMainNavigation();
    
    // 2. USER MENU DROPDOWN
    initUserMenu();
    
    // 3. DARK MODE TOGGLE
    initDarkMode();
    
    // 4. SEARCH FUNCTIONALITY
    initSearch();
    
    console.log('✅ Stok Saat Ini: ALL components initialized!');
}

// 1. Main Navigation Function
function initMainNavigation() {
    console.log('📱 Stok Saat Ini: Setting up main navigation...');
    
    const menuToggle = document.getElementById('menuToggle');
    const offcanvasNav = document.getElementById('offcanvasNav');
    const offcanvasOverlay = document.getElementById('offcanvasOverlay');
    const offcanvasClose = document.getElementById('offcanvasClose');
    
    if (!menuToggle || !offcanvasNav || !offcanvasOverlay) {
        console.error('❌ Stok Saat Ini: Missing main navigation elements!');
        return;
    }
    
    // Remove existing listeners by cloning
    const newMenuToggle = menuToggle.cloneNode(true);
    menuToggle.parentNode.replaceChild(newMenuToggle, menuToggle);
    
    // Add click handler
    newMenuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🔄 Stok Saat Ini: Main menu clicked');
        
        const isOpen = offcanvasNav.classList.contains('show');
        
        if (isOpen) {
            offcanvasNav.classList.remove('show');
            offcanvasOverlay.classList.remove('show');
            newMenuToggle.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            offcanvasNav.classList.add('show');
            offcanvasOverlay.classList.add('show');
            newMenuToggle.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    });
    
    // Close handlers
    if (offcanvasClose) {
        offcanvasClose.addEventListener('click', function() {
            offcanvasNav.classList.remove('show');
            offcanvasOverlay.classList.remove('show');
            newMenuToggle.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    offcanvasOverlay.addEventListener('click', function() {
        offcanvasNav.classList.remove('show');
        offcanvasOverlay.classList.remove('show');
        newMenuToggle.classList.remove('active');
        document.body.style.overflow = '';
    });
    
    console.log('✅ Stok Saat Ini: Main navigation ready!');
}

// 2. User Menu Function
function initUserMenu() {
    console.log('👤 Stok Saat Ini: Setting up user menu...');
    
    const userMenuToggle = document.getElementById('userMenuToggle');
    const userDropdown = document.getElementById('userDropdown');
    
    if (!userMenuToggle || !userDropdown) {
        console.error('❌ Stok Saat Ini: Missing user menu elements!');
        return;
    }
    
    // Remove existing listeners by cloning
    const newUserToggle = userMenuToggle.cloneNode(true);
    userMenuToggle.parentNode.replaceChild(newUserToggle, userMenuToggle);
    
    // Add click handler
    newUserToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('👤 Stok Saat Ini: User menu clicked');
        
        const isOpen = userDropdown.classList.contains('show');
        
        if (isOpen) {
            userDropdown.classList.remove('show');
            console.log('📁 Stok Saat Ini: User menu closed');
        } else {
            userDropdown.classList.add('show');
            console.log('📂 Stok Saat Ini: User menu opened');
        }
    });
    
    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!newUserToggle.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('show');
        }
    });
    
    console.log('✅ Stok Saat Ini: User menu ready!');
}

// 3. Dark Mode Function
function initDarkMode() {
    console.log('🌙 Stok Saat Ini: Setting up dark mode...');
    
    const themeSwitch = document.getElementById('themeSwitch');
    
    if (!themeSwitch) {
        console.error('❌ Stok Saat Ini: Missing dark mode toggle!');
        return;
    }
    
    // Get current theme
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);
    
    // Update switch state
    if (currentTheme === 'dark') {
        themeSwitch.classList.add('active');
    }
    
    // Remove existing listeners by cloning
    const newThemeSwitch = themeSwitch.cloneNode(true);
    themeSwitch.parentNode.replaceChild(newThemeSwitch, themeSwitch);
    
    // Add click handler
    newThemeSwitch.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🌙 Stok Saat Ini: Dark mode toggle clicked');
        
        const newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        newThemeSwitch.classList.toggle('active', newTheme === 'dark');
        
        console.log('🎨 Stok Saat Ini: Theme changed to:', newTheme);
    });
    
    console.log('✅ Stok Saat Ini: Dark mode ready!');
}

// 4. Search Function
function initSearch() {
    console.log('🔍 Stok Saat Ini: Setting up search...');
    
    const quickSearch = document.getElementById('quickSearch');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchClose = document.getElementById('searchClose');
    
    if (!quickSearch || !searchOverlay) {
        console.log('⚠️ Stok Saat Ini: Search elements not found (optional)');
        return;
    }
    
    // Quick search button
    quickSearch.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('🔍 Stok Saat Ini: Search opened');
        searchOverlay.classList.add('show');
    });
    
    // Close search
    if (searchClose) {
        searchClose.addEventListener('click', function() {
            console.log('❌ Stok Saat Ini: Search closed');
            searchOverlay.classList.remove('show');
        });
    }
    
    // Close on overlay click
    searchOverlay.addEventListener('click', function(e) {
        if (e.target === searchOverlay) {
            searchOverlay.classList.remove('show');
        }
    });
    
    console.log('✅ Stok Saat Ini: Search ready!');
}

// Escape key handler for all components
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Close all open components
        const offcanvasNav = document.getElementById('offcanvasNav');
        const userDropdown = document.getElementById('userDropdown');
        const searchOverlay = document.getElementById('searchOverlay');
        const menuToggle = document.getElementById('menuToggle');
        
        if (offcanvasNav && offcanvasNav.classList.contains('show')) {
            offcanvasNav.classList.remove('show');
            document.getElementById('offcanvasOverlay').classList.remove('show');
            if (menuToggle) menuToggle.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        if (userDropdown) userDropdown.classList.remove('show');
        if (searchOverlay) searchOverlay.classList.remove('show');
    }
});

// Initialize everything
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeAllComponents);
} else {
    initializeAllComponents();
}

// Backup initialization
setTimeout(initializeAllComponents, 1000);
</script>

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

.nav-links .btn-success {
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.2) 0%, rgba(76, 175, 80, 0.1) 100%);
    color: #4caf50;
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

.nav-links .btn-success:hover {
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.3) 0%, rgba(76, 175, 80, 0.2) 100%);
}

/* Stock level indicators */
.stock-level {
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: inline-block;
    min-width: 80px;
    text-align: center;
}

.stock-high {
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.2) 0%, rgba(76, 175, 80, 0.1) 100%);
    color: #4caf50;
}

.stock-medium {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.2) 0%, rgba(255, 193, 7, 0.1) 100%);
    color: #ffc107;
}

.stock-low {
    background: linear-gradient(135deg, rgba(255, 152, 0, 0.2) 0%, rgba(255, 152, 0, 0.1) 100%);
    color: #ff9800;
}

.stock-critical {
    background: linear-gradient(135deg, rgba(244, 67, 54, 0.2) 0%, rgba(244, 67, 54, 0.1) 100%);
    color: #f44336;
}

.stock-empty {
    background: linear-gradient(135deg, rgba(158, 158, 158, 0.2) 0%, rgba(158, 158, 158, 0.1) 100%);
    color: #9e9e9e;
}

/* Real-time indicator */
.realtime-indicator {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(76, 175, 80, 0.05) 100%);
    border: 1px solid rgba(76, 175, 80, 0.2);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    color: #4caf50;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.realtime-dot {
    width: 8px;
    height: 8px;
    background: #4caf50;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
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

/* Responsive design */
@media (max-width: 768px) {
    .glass-container {
        padding: 16px;
        margin: 16px 0;
        border-radius: 16px;
    }
    
    .glass-table {
        font-size: 14px;
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
    
    .stock-level {
        font-size: 11px;
        padding: 4px 8px;
        min-width: 60px;
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
    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;">
        <h2>📊 Stok Saat Ini</h2>
        <div class="realtime-indicator">
            <div class="realtime-dot"></div>
            Real-Time
        </div>
    </div>
    
    <!-- Statistics Overview -->
    <div class="stats-overview">
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['total_items']); ?></div>
            <div class="stat-label">📦 Total Item</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['total_stock']); ?></div>
            <div class="stat-label">📊 Total Stok</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['good_stock']); ?></div>
            <div class="stat-label">✅ Stok Baik</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['low_stock']); ?></div>
            <div class="stat-label">⚠️ Stok Rendah</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['empty_stock']); ?></div>
            <div class="stat-label">❌ Stok Kosong</div>
        </div>
    </div>
    
    <div class="nav-links">
        <a href="../../pages/dashboard.php" class="btn btn-secondary">
            ← Kembali ke Dashboard
        </a>
        <a href="../stok_masuk/tambah.php" class="btn btn-primary">
            📦 Tambah Stok Masuk
        </a>
        <a href="../stok_keluar/catat_stok.php" class="btn btn-success">
            📤 Catat Stok Keluar
        </a>
    </div>

    <div class="glass-container">
        <table class="glass-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Lokasi</th>
                    <th>Jumlah Stok</th>
                    <th>Status</th>
                    <th>Terakhir Masuk</th>
                    <th>Terakhir Keluar</th>
                    <th>Terakhir Diperbarui</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= htmlspecialchars($row['kode_produk']); ?></strong></td>
                            <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                            <td><?= htmlspecialchars($row['nama_lokasi']); ?></td>
                            <td><?= number_format($row['jumlah_stok']); ?> unit</td>
                            <td>
                                <?php
                                $stok = $row['jumlah_stok'];
                                $minimal = $row['stok_minimal'] ?? 10;
                                $statusClass = 'stock-empty';
                                $statusText = 'Kosong';
                                
                                if ($stok > $minimal * 2) {
                                    $statusClass = 'stock-high';
                                    $statusText = 'Tinggi';
                                } elseif ($stok > $minimal) {
                                    $statusClass = 'stock-medium';
                                    $statusText = 'Sedang';
                                } elseif ($stok > 0 && $stok <= $minimal) {
                                    $statusClass = 'stock-low';
                                    $statusText = 'Rendah';
                                } elseif ($stok == 0) {
                                    $statusClass = 'stock-empty';
                                    $statusText = 'Kosong';
                                }
                                ?>
                                <span class="stock-level <?= $statusClass; ?>">
                                    <?= $statusText; ?>
                                </span>
                            </td>
                            <td><?= $row['tanggal_terakhir_masuk'] ? date('d/m/Y', strtotime($row['tanggal_terakhir_masuk'])) : '-'; ?></td>
                            <td><?= $row['tanggal_terakhir_keluar'] ? date('d/m/Y', strtotime($row['tanggal_terakhir_keluar'])) : '-'; ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['tanggal_diperbarui'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px; color: var(--md-sys-color-on-surface-variant);">
                            📊 Tidak ada data stok tersedia
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Include the footer template
require_once '../../templates/footer.php';
?>
