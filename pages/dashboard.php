<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

// Set page title for the header
$page_title = "Dashboard - Stock Control Gudang";

// Include the header template with navigation
require_once '../templates/header.php';
require_once '../config/database.php';

// Get some basic statistics for dashboard
$stats = [];

// Count products
$result = $koneksi->query("SELECT COUNT(*) as count FROM produk");
$stats['produk'] = $result->fetch_assoc()['count'];

// Count suppliers
$result = $koneksi->query("SELECT COUNT(*) as count FROM supplier");
$stats['supplier'] = $result->fetch_assoc()['count'];

// Count locations
$result = $koneksi->query("SELECT COUNT(*) as count FROM lokasi_gudang");
$stats['lokasi'] = $result->fetch_assoc()['count'];

// Count customers
$result = $koneksi->query("SELECT COUNT(*) as count FROM pelanggan");
$stats['pelanggan'] = $result->fetch_assoc()['count'];

// Get low stock items
$result = $koneksi->query("SELECT COUNT(*) as count FROM stok_saat_ini WHERE jumlah_stok <= 10");
$stats['low_stock'] = $result->fetch_assoc()['count'];
?>

<!-- COMPREHENSIVE: All Interactive Elements for Dashboard -->
<script>
console.log('🔧 Dashboard: Loading ALL interactive elements...');

// Wait for DOM and force initialize ALL components
function initializeAllComponents() {
    console.log('🚀 Dashboard: Initializing ALL components...');
    
    // 1. MAIN NAVIGATION
    initMainNavigation();
    
    // 2. USER MENU DROPDOWN
    initUserMenu();
    
    // 3. DARK MODE TOGGLE
    initDarkMode();
    
    // 4. SEARCH FUNCTIONALITY
    initSearch();
    
    console.log('✅ Dashboard: ALL components initialized!');
}

// 1. Main Navigation Function
function initMainNavigation() {
    console.log('📱 Dashboard: Setting up main navigation...');
    
    const menuToggle = document.getElementById('menuToggle');
    const offcanvasNav = document.getElementById('offcanvasNav');
    const offcanvasOverlay = document.getElementById('offcanvasOverlay');
    const offcanvasClose = document.getElementById('offcanvasClose');
    
    if (!menuToggle || !offcanvasNav || !offcanvasOverlay) {
        console.error('❌ Dashboard: Missing main navigation elements!');
        return;
    }
    
    // Remove existing listeners by cloning
    const newMenuToggle = menuToggle.cloneNode(true);
    menuToggle.parentNode.replaceChild(newMenuToggle, menuToggle);
    
    // Add click handler
    newMenuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🔄 Dashboard: Main menu clicked');
        
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
    
    console.log('✅ Dashboard: Main navigation ready!');
}

// 2. User Menu Function
function initUserMenu() {
    console.log('👤 Dashboard: Setting up user menu...');
    
    const userMenuToggle = document.getElementById('userMenuToggle');
    const userDropdown = document.getElementById('userDropdown');
    
    if (!userMenuToggle || !userDropdown) {
        console.error('❌ Dashboard: Missing user menu elements!');
        return;
    }
    
    // Remove existing listeners by cloning
    const newUserToggle = userMenuToggle.cloneNode(true);
    userMenuToggle.parentNode.replaceChild(newUserToggle, userMenuToggle);
    
    // Add click handler
    newUserToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('👤 Dashboard: User menu clicked');
        
        const isOpen = userDropdown.classList.contains('show');
        
        if (isOpen) {
            userDropdown.classList.remove('show');
            console.log('📁 Dashboard: User menu closed');
        } else {
            userDropdown.classList.add('show');
            console.log('📂 Dashboard: User menu opened');
        }
    });
    
    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (!newUserToggle.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('show');
        }
    });
    
    console.log('✅ Dashboard: User menu ready!');
}

// 3. Dark Mode Function
function initDarkMode() {
    console.log('🌙 Dashboard: Setting up dark mode...');
    
    const themeSwitch = document.getElementById('themeSwitch');
    
    if (!themeSwitch) {
        console.error('❌ Dashboard: Missing dark mode toggle!');
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
        console.log('🌙 Dashboard: Dark mode toggle clicked');
        
        const newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        newThemeSwitch.classList.toggle('active', newTheme === 'dark');
        
        console.log('🎨 Dashboard: Theme changed to:', newTheme);
    });
    
    console.log('✅ Dashboard: Dark mode ready!');
}

// 4. Search Function
function initSearch() {
    console.log('🔍 Dashboard: Setting up search...');
    
    const quickSearch = document.getElementById('quickSearch');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchClose = document.getElementById('searchClose');
    
    if (!quickSearch || !searchOverlay) {
        console.log('⚠️ Dashboard: Search elements not found (optional)');
        return;
    }
    
    // Quick search button
    quickSearch.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('🔍 Dashboard: Search opened');
        searchOverlay.classList.add('show');
    });
    
    // Close search
    if (searchClose) {
        searchClose.addEventListener('click', function() {
            console.log('❌ Dashboard: Search closed');
            searchOverlay.classList.remove('show');
        });
    }
    
    // Close on overlay click
    searchOverlay.addEventListener('click', function(e) {
        if (e.target === searchOverlay) {
            searchOverlay.classList.remove('show');
        }
    });
    
    console.log('✅ Dashboard: Search ready!');
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
/* Dashboard-specific styles */
.dashboard-welcome {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 32px;
    margin: 20px 0;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    text-align: center;
    position: relative;
    overflow: hidden;
}

[data-theme="dark"] .dashboard-welcome {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    margin: 32px 0;
}

.dashboard-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s ease;
    text-decoration: none;
    color: var(--md-sys-color-on-surface);
    position: relative;
    overflow: hidden;
    display: block;
}

[data-theme="dark"] .dashboard-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    border-color: var(--md-sys-color-primary);
    text-decoration: none;
    color: var(--md-sys-color-on-surface);
}

.card-icon {
    font-size: 48px;
    margin-bottom: 16px;
    display: block;
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--md-sys-color-on-surface);
}

.card-description {
    font-size: 14px;
    color: var(--md-sys-color-on-surface-variant);
    opacity: 0.8;
    margin-bottom: 12px;
}

.card-stat {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: rgba(103, 80, 164, 0.1);
    border: 1px solid rgba(103, 80, 164, 0.2);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    color: var(--md-sys-color-primary);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin: 24px 0;
}

.stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
}

[data-theme="dark"] .stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: var(--md-sys-color-primary);
    margin-bottom: 8px;
}

.stat-label {
    font-size: 14px;
    color: var(--md-sys-color-on-surface-variant);
    font-weight: 500;
}

.low-stock-warning {
    border-color: #ff9800 !important;
    background: linear-gradient(135deg, rgba(255, 152, 0, 0.1) 0%, rgba(255, 152, 0, 0.05) 100%);
}

.low-stock-warning .stat-number {
    color: #ff9800;
}

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .dashboard-welcome {
        padding: 24px;
        margin: 16px 0;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .stat-card {
        padding: 16px;
    }
    
    .stat-number {
        font-size: 24px;
    }
}

/* Add floating particles effect */
.dashboard-welcome .particle {
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
    <div class="dashboard-welcome">
        <h1>🏠 Dashboard Stock Control</h1>
        <p>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! Kelola seluruh informasi gudang Anda dengan mudah.</p>
    </div>

    <!-- Statistics Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['produk']); ?></div>
            <div class="stat-label">📦 Total Produk</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['supplier']); ?></div>
            <div class="stat-label">🏢 Total Supplier</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['lokasi']); ?></div>
            <div class="stat-label">📍 Lokasi Gudang</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['pelanggan']); ?></div>
            <div class="stat-label">👥 Total Pelanggan</div>
        </div>
        <div class="stat-card <?= $stats['low_stock'] > 0 ? 'low-stock-warning' : ''; ?>">
            <div class="stat-number"><?= number_format($stats['low_stock']); ?></div>
            <div class="stat-label">⚠️ Stok Rendah</div>
        </div>
    </div>

    <!-- Main Navigation Cards -->
    <div class="dashboard-grid">
        <a href="produk/index.php" class="dashboard-card">
            <span class="card-icon">📦</span>
            <h3 class="card-title">Data Produk</h3>
            <p class="card-description">Kelola informasi produk, kategori, dan spesifikasi barang</p>
            <div class="card-stat">
                <span>📊</span>
                <span><?= number_format($stats['produk']); ?> produk</span>
            </div>
        </a>

        <a href="lokasi/index.php" class="dashboard-card">
            <span class="card-icon">📍</span>
            <h3 class="card-title">Lokasi Gudang</h3>
            <p class="card-description">Atur lokasi penyimpanan dan kapasitas gudang</p>
            <div class="card-stat">
                <span>🏠</span>
                <span><?= number_format($stats['lokasi']); ?> lokasi</span>
            </div>
        </a>

        <a href="supplier/index.php" class="dashboard-card">
            <span class="card-icon">🏢</span>
            <h3 class="card-title">Data Supplier</h3>
            <p class="card-description">Kelola informasi pemasok dan kontak bisnis</p>
            <div class="card-stat">
                <span>🤝</span>
                <span><?= number_format($stats['supplier']); ?> supplier</span>
            </div>
        </a>

        <a href="pelanggan/index.php" class="dashboard-card">
            <span class="card-icon">👥</span>
            <h3 class="card-title">Data Pelanggan</h3>
            <p class="card-description">Manajemen data pelanggan dan riwayat transaksi</p>
            <div class="card-stat">
                <span>👤</span>
                <span><?= number_format($stats['pelanggan']); ?> pelanggan</span>
            </div>
        </a>

        <a href="stok_masuk/index.php" class="dashboard-card">
            <span class="card-icon">📦</span>
            <h3 class="card-title">Stok Masuk</h3>
            <p class="card-description">Catat dan pantau barang yang masuk ke gudang</p>
            <div class="card-stat">
                <span>📈</span>
                <span>Kelola masuk</span>
            </div>
        </a>

        <a href="stok_keluar/index.php" class="dashboard-card">
            <span class="card-icon">📤</span>
            <h3 class="card-title">Stok Keluar</h3>
            <p class="card-description">Kelola pengeluaran barang dan distribusi</p>
            <div class="card-stat">
                <span>📉</span>
                <span>Kelola keluar</span>
            </div>
        </a>

        <a href="stok_saat_ini/index.php" class="dashboard-card">
            <span class="card-icon">📊</span>
            <h3 class="card-title">Stok Saat Ini</h3>
            <p class="card-description">Monitor stok real-time dan status ketersediaan</p>
            <div class="card-stat <?= $stats['low_stock'] > 0 ? 'low-stock-warning' : ''; ?>">
                <span>⚠️</span>
                <span><?= $stats['low_stock']; ?> stok rendah</span>
            </div>
        </a>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Add floating particles to welcome section
    const welcomeSection = document.querySelector('.dashboard-welcome');
    if (welcomeSection) {
        createFloatingParticles(welcomeSection);
    }
    
    // Add hover effects to dashboard cards
    const dashboardCards = document.querySelectorAll('.dashboard-card');
    dashboardCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-6px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add click animation to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
});

function createFloatingParticles(container) {
    // Create subtle floating particles for premium effect
    for (let i = 0; i < 6; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = 8 + Math.random() * 4 + 's';
        container.appendChild(particle);
    }
}

// Show welcome message for new sessions
if (sessionStorage.getItem('dashboard_welcomed') !== 'true') {
    setTimeout(() => {
        console.log('🎉 Welcome to Stock Control Dashboard!');
        sessionStorage.setItem('dashboard_welcomed', 'true');
    }, 1000);
}
</script>

<?php
require_once '../templates/footer.php';
?>
