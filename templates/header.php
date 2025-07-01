<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Stock Control Gudang'; ?></title>
    
    <!-- Material 3 Design System Styles -->
<?php
// Tentukan path CSS yang benar dengan urutan pengecekan yang tepat
$css_path = '';
$nav_css_path = '';
$self_path = $_SERVER['PHP_SELF'];

if (strpos($self_path, '/pages/supplier/') !== false || 
    strpos($self_path, '/pages/produk/') !== false ||
    strpos($self_path, '/pages/lokasi/') !== false ||
    strpos($self_path, '/pages/pelanggan/') !== false ||
    strpos($self_path, '/pages/stok_masuk/') !== false ||
    strpos($self_path, '/pages/stok_keluar/') !== false ||
    strpos($self_path, '/pages/stok_saat_ini/') !== false) {
    // 1. Cek folder yang lebih dalam DULU (seperti /pages/produk/)
    $css_path = '../../assets/css/material3-styles.css';
    $nav_css_path = '../../assets/css/navigation.css';
    $nav_base_path = '../../pages/';
} elseif (strpos($self_path, '/pages/') !== false) {
    // 2. BARU cek folder /pages/ (untuk dashboard)
    $css_path = '../assets/css/material3-styles.css';
    $nav_css_path = '../assets/css/navigation.css';
    $nav_base_path = '../pages/';
} else {
    // 3. Jika tidak di dalam /pages/ (seperti login.php di root)
    $css_path = 'assets/css/material3-styles.css';
    $nav_css_path = 'assets/css/navigation.css';
    $nav_base_path = 'pages/';
}

// Set navigation paths based on current location
$dashboard_path = $nav_base_path . 'dashboard.php';
$produk_path = $nav_base_path . 'produk/index.php';
$lokasi_path = $nav_base_path . 'lokasi/index.php';
$supplier_path = $nav_base_path . 'supplier/index.php';
$pelanggan_path = $nav_base_path . 'pelanggan/index.php';
$stok_masuk_path = $nav_base_path . 'stok_masuk/index.php';
$stok_keluar_path = $nav_base_path . 'stok_keluar/index.php';
$stok_saat_ini_path = $nav_base_path . 'stok_saat_ini/index.php';
$tambah_stok_masuk_path = $nav_base_path . 'stok_masuk/tambah.php';
$catat_stok_keluar_path = $nav_base_path . 'stok_keluar/catat_stok.php';
$tambah_produk_path = $nav_base_path . 'produk/tambah.php';
$tambah_supplier_path = $nav_base_path . 'supplier/tambah.php';

// Logout path logic
if (strpos($self_path, '/pages/') !== false) {
    $logout_path = '../logout.php';
} else {
    $logout_path = 'logout.php';
}
?>
    <link rel="stylesheet" href="<?php echo $css_path; ?>">
    <link rel="stylesheet" href="<?php echo $nav_css_path; ?>">
    
    <!-- Preload Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    
    <!-- Meta tags -->
    <meta name="description" content="Sistem manajemen stok gudang">
    <meta name="theme-color" content="#6750A4">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📦</text></svg>">
    
    <!-- Additional page-specific styles can be added here -->
    <?php if (isset($additional_css)): ?>
        <?= $additional_css ?>
    <?php endif; ?>
</head>
<body>
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Theme Toggle Button -->
    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle dark mode">
        <span class="theme-toggle-icon">🌙</span>
    </button>

    <!-- Main Navigation Bar -->
    <nav class="main-navbar" id="mainNavbar">
        <div class="navbar-container">
            <!-- Brand/Logo Section -->
            <div class="navbar-brand">
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
                <div class="brand-content">
                    <div class="brand-icon">📦</div>
                    <div class="brand-text">
                        <h1 class="brand-title">Stock Control</h1>
                        <span class="brand-subtitle">Gudang Management</span>
                    </div>
                </div>
            </div>

            <!-- Center Navigation (Desktop) -->
            <div class="navbar-center">
                <div class="nav-breadcrumb">
                    <span class="breadcrumb-item">
                        <?php 
                        $current_page = basename($_SERVER['PHP_SELF'], '.php');
                        $page_names = [
                            'dashboard' => '🏠 Dashboard',
                            'index' => '📋 Data',
                            'tambah' => '➕ Tambah',
                            'edit' => '✏️ Edit',
                            'catat_stok' => '📤 Catat Stok'
                        ];
                        echo $page_names[$current_page] ?? '📄 ' . ucfirst($current_page);
                        ?>
                    </span>
                </div>
            </div>

            <!-- Right Section -->
            <div class="navbar-right">
                <!-- Enhanced Quick Actions>
                <div class="quick-actions">
                    <button class="quick-action-btn enhanced" id="quickSearch" title="Global Search">
                        <span class="action-icon">🔍</span>
                        <span class="action-label">Search</span>
                    </button>
                </div-->

                <!-- User Menu -->
                <?php if (isset($_SESSION['username'])): ?>
                <div class="user-menu">
                    <button class="user-menu-toggle" id="userMenuToggle">
                        <div class="user-avatar">
                            <span class="user-initial"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></span>
                        </div>
                        <div class="user-info">
                            <span class="user-name"><?= htmlspecialchars($_SESSION['username']) ?></span>
                            <span class="user-role">Administrator</span>
                        </div>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    
                    <!-- Enhanced User Dropdown -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header-enhanced">
                            <div class="user-avatar-large">
                                <span class="user-initial"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></span>
                                <div class="online-indicator"></div>
                            </div>
                            <div class="user-details-enhanced">
                                <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                                <small>Administrator</small>
                                <div class="user-status">
                                    <span class="status-dot"></span>
                                    <span>Online</span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
            
                        <!-- Quick Actions Section -->
                        <div class="account-section">
                            <h4 class="section-title">Quick Actions</h4>
                            <a href="<?= $tambah_stok_masuk_path ?>" class="dropdown-item-enhanced">
                                <div class="item-icon-container primary">
                                    <span class="item-icon">📦</span>
                                </div>
                                <div class="item-content">
                                    <span class="item-title">Tambah Stok</span>
                                    <span class="item-subtitle">Buat Entri stok baru</span>
                                </div>
                            </a>
                            <a href="<?= $catat_stok_keluar_path ?>" class="dropdown-item-enhanced">
                                <div class="item-icon-container secondary">
                                    <span class="item-icon">📤</span>
                                </div>
                                <div class="item-content">
                                    <span class="item-title">Stock Keluar</span>
                                    <span class="item-subtitle">Data stok keluar</span>
                                </div>
                            </a>
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        <a href="<?= $logout_path ?>" class="dropdown-item-enhanced logout-enhanced">
                            <div class="item-icon-container danger">
                                <span class="item-icon">🚪</span>
                            </div>
                            <div class="item-content">
                                <span class="item-title">Logout</span>
                                <span class="item-subtitle">Keluar dari akun anda.</span>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Search Overlay >
    <div class="search-overlay" id="searchOverlay">
        <div class="search-container">
            <div class="search-header">
                <h3>🔍 Pencarian Cepat</h3>
                <button class="search-close" id="searchClose">✕</button>
            </div>
            <div class="search-input-container">
                <input type="text" class="search-input" placeholder="Cari produk, lokasi, supplier..." id="globalSearch">
                <button class="search-btn">Cari</button>
            </div>
            <div class="search-suggestions">
                <div class="suggestion-category">
                    <h4>Pencarian Populer</h4>
                    <div class="suggestion-items">
                        <span class="suggestion-tag">Stok Rendah</span>
                        <span class="suggestion-tag">Produk Terbaru</span>
                        <span class="suggestion-tag">Supplier Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div-->

    <!-- Offcanvas Navigation Pane -->
    <div class="offcanvas-overlay" id="offcanvasOverlay"></div>
    <nav class="offcanvas-nav" id="offcanvasNav">
        <div class="offcanvas-header">
            <div class="offcanvas-brand">
                <div class="brand-icon-large">📦</div>
                <div class="brand-info">
                    <h2>Stock Control</h2>
                    <p>Sistem Manajemen Gudang</p>
                </div>
            </div>
            <button class="offcanvas-close" id="offcanvasClose">✕</button>
        </div>

        <div class="offcanvas-content">
            <!-- Main Navigation -->
            <div class="nav-section">
                <h3 class="nav-section-title">📊 Dashboard</h3>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="<?= $dashboard_path ?>" class="nav-link">
                            <span class="nav-icon">🏠</span>
                            <span class="nav-text">Dashboard Utama</span>
                            <span class="nav-badge">Home</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Master Data Section -->
            <div class="nav-section">
                <h3 class="nav-section-title">📋 Master Data</h3>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="<?= $produk_path ?>" class="nav-link">
                            <span class="nav-icon">📦</span>
                            <span class="nav-text">Data Produk</span>
                            <span class="nav-arrow">→</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $lokasi_path ?>" class="nav-link">
                            <span class="nav-icon">📍</span>
                            <span class="nav-text">Lokasi Gudang</span>
                            <span class="nav-arrow">→</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $supplier_path ?>" class="nav-link">
                            <span class="nav-icon">🏢</span>
                            <span class="nav-text">Data Supplier</span>
                            <span class="nav-arrow">→</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $pelanggan_path ?>" class="nav-link">
                            <span class="nav-icon">👥</span>
                            <span class="nav-text">Data Pelanggan</span>
                            <span class="nav-arrow">→</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Stock Management Section -->
            <div class="nav-section">
                <h3 class="nav-section-title">📈 Manajemen Stok</h3>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="<?= $stok_masuk_path ?>" class="nav-link">
                            <span class="nav-icon">📦</span>
                            <span class="nav-text">Stok Masuk</span>
                            <span class="nav-badge new">New</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $stok_keluar_path ?>" class="nav-link">
                            <span class="nav-icon">📤</span>
                            <span class="nav-text">Stok Keluar</span>
                            <span class="nav-arrow">→</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $stok_saat_ini_path ?>" class="nav-link">
                            <span class="nav-icon">📊</span>
                            <span class="nav-text">Stok Saat Ini</span>
                            <span class="nav-badge live">Live</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Quick Actions Section -->
            <div class="nav-section">
                <h3 class="nav-section-title">⚡ Aksi Cepat</h3>
                <div class="quick-action-grid">
                    <a href="<?= $tambah_stok_masuk_path ?>" class="quick-action-card">
                        <div class="action-icon">📦</div>
                        <div class="action-text">
                            <strong>Tambah Stok</strong>
                            <small>Stok Masuk Baru</small>
                        </div>
                    </a>
                    <a href="<?= $catat_stok_keluar_path ?>" class="quick-action-card">
                        <div class="action-icon">📤</div>
                        <div class="action-text">
                            <strong>Catat Keluar</strong>
                            <small>Stok Keluar</small>
                        </div>
                    </a>
                    <a href="<?= $tambah_produk_path ?>" class="quick-action-card">
                        <div class="action-icon">➕</div>
                        <div class="action-text">
                            <strong>Produk Baru</strong>
                            <small>Tambah Produk</small>
                        </div>
                    </a>
                    <a href="<?= $tambah_supplier_path ?>" class="quick-action-card">
                        <div class="action-icon">🏢</div>
                        <div class="action-text">
                            <strong>Supplier Baru</strong>
                            <small>Tambah Supplier</small>
                        </div>
                    </a>
                </div>
            </div>

            <!-- System Info Section -->
            <div class="nav-section">
                <div class="system-info">
                    <div class="info-item">
                        <span class="info-label">Status Sistem</span>
                        <span class="info-value online">🟢 Online</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Versi</span>
                        <span class="info-value">v0.1.0a dev</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Terakhir diperbaharui</span>
                        <span class="info-value"><?= date('d/m/Y H:i') ?></span>
                    </div>
                </div>
            </div>

            <!-- Dark Mode Toggle Section -->
            <div class="nav-section">
                <h3 class="nav-section-title">🎨 Tema</h3>
                <div class="theme-toggle-container">
                    <div class="theme-toggle-option">
                        <span class="theme-icon">☀️</span>
                        <span class="theme-label">Mode Terang</span>
                        <div class="theme-switch" id="themeSwitch">
                            <div class="theme-switch-slider"></div>
                        </div>
                        <span class="theme-icon">🌙</span>
                        <span class="theme-label">Mode Gelap</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offcanvas Footer -->
        <?php if (isset($_SESSION['username'])): ?>
        <div class="offcanvas-footer">
            <div class="footer-user">
                <div class="user-avatar-small">
                    <span><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></span>
                </div>
                <div class="user-details">
                    <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                    <small>Masuk pada <?= date('H:i') ?></small>
                </div>
            </div>
            <a href="<?= $logout_path ?>" class="logout-btn">
                <span>🚪</span>
            </a>
        </div>
        <?php endif; ?>
    </nav>
    
    <!-- Main Content Area -->
    <main id="main-content" class="main-content">
<!-- Navigation JavaScript - Simple and Reliable -->
<!-- Simple, Reliable Navigation Script -->
<script>
// Immediate navigation setup - no waiting for external files
(function() {
    'use strict';
    
    function initNavigation() {
        console.log('🚀 Navigation initializing...');
        
        const menuToggle = document.getElementById('menuToggle');
        const offcanvasNav = document.getElementById('offcanvasNav');
        const offcanvasOverlay = document.getElementById('offcanvasOverlay');
        const offcanvasClose = document.getElementById('offcanvasClose');
        
        if (!menuToggle || !offcanvasNav || !offcanvasOverlay) {
            console.error('❌ Navigation elements missing:', {
                menuToggle: !!menuToggle,
                offcanvasNav: !!offcanvasNav,
                offcanvasOverlay: !!offcanvasOverlay
            });
            return;
        }
        
        console.log('✅ All navigation elements found');
        
        // Toggle navigation
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('🔄 Menu toggle clicked');
            
            const isOpen = offcanvasNav.classList.contains('show');
            
            if (isOpen) {
                closeNav();
            } else {
                openNav();
            }
        });
        
        // Close button
        if (offcanvasClose) {
            offcanvasClose.addEventListener('click', closeNav);
        }
        
        // Overlay click
        offcanvasOverlay.addEventListener('click', closeNav);
        
        // Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeNav();
            }
        });
        
        function openNav() {
            console.log('📂 Opening navigation');
            offcanvasNav.classList.add('show');
            offcanvasOverlay.classList.add('show');
            menuToggle.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeNav() {
            console.log('📁 Closing navigation');
            offcanvasNav.classList.remove('show');
            offcanvasOverlay.classList.remove('show');
            menuToggle.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        console.log('✅ Navigation setup complete!');
    }
    
    // Initialize immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavigation);
    } else {
        initNavigation();
    }
    
    // Dark mode toggle
    function initDarkMode() {
        const themeSwitch = document.getElementById('themeSwitch');
        const currentTheme = localStorage.getItem('theme') || 'light';
        
        document.documentElement.setAttribute('data-theme', currentTheme);
        
        if (themeSwitch) {
            if (currentTheme === 'dark') {
                themeSwitch.classList.add('active');
            }
            
            themeSwitch.addEventListener('click', function() {
                const newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                themeSwitch.classList.toggle('active', newTheme === 'dark');
            });
        }
    }
    
    // Search functionality
    function initSearch() {
        const quickSearch = document.getElementById('quickSearch');
        const searchOverlay = document.getElementById('searchOverlay');
        const searchClose = document.getElementById('searchClose');
        
        if (quickSearch && searchOverlay) {
            quickSearch.addEventListener('click', function(e) {
                e.preventDefault();
                searchOverlay.classList.add('show');
            });
        }
        
        if (searchClose) {
            searchClose.addEventListener('click', function() {
                searchOverlay.classList.remove('show');
            });
        }
        
        if (searchOverlay) {
            searchOverlay.addEventListener('click', function(e) {
                if (e.target === searchOverlay) {
                    searchOverlay.classList.remove('show');
                }
            });
        }
    }
    
    // User menu functionality
    function initUserMenu() {
        const userMenuToggle = document.getElementById('userMenuToggle');
        const userDropdown = document.getElementById('userDropdown');
        
        if (userMenuToggle && userDropdown) {
            userMenuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });
            
            document.addEventListener('click', function(e) {
                if (!userMenuToggle.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }
    }
    
    // Initialize all features
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initDarkMode();
            initSearch();
            initUserMenu();
        });
    } else {
        initDarkMode();
        initSearch();
        initUserMenu();
    }
})();
</script>
