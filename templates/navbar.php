<!-- Main Navigation Bar -->
<nav class="main-navbar" id="mainNavbar">
    <div class="navbar-container">
        <!-- Brand/Logo Section -->
        <div class="navbar-brand">
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation" style="
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    gap: 3px;
    padding: 8px;
    z-index: 2000;
">
    <span class="hamburger-line" style="
        width: 20px;
        height: 2px;
        background: #ffffff;
        border-radius: 1px;
        transition: all 0.3s ease;
        display: block;
    "></span>
    <span class="hamburger-line" style="
        width: 20px;
        height: 2px;
        background: #ffffff;
        border-radius: 1px;
        transition: all 0.3s ease;
        display: block;
    "></span>
    <span class="hamburger-line" style="
        width: 20px;
        height: 2px;
        background: #ffffff;
        border-radius: 1px;
        transition: all 0.3s ease;
        display: block;
    "></span>
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
            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="quick-action-btn" id="quickStockIn" title="Tambah Stok Masuk">
                    <span class="action-icon">📦</span>
                </button>
                <button class="quick-action-btn" id="quickStockOut" title="Catat Stok Keluar">
                    <span class="action-icon">📤</span>
                </button>
                <!--button class="quick-action-btn" id="quickSearch" title="Pencarian">
                    <span class="action-icon">🔍</span>
                </button-->
            </div>

            <!-- User Menu -->
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
                
                <!-- User Dropdown -->
                <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-header">
                        <div class="user-avatar-large">
                            <span class="user-initial"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></span>
                        </div>
                        <div class="user-details">
                            <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                            <small>Administrator</small>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <span class="item-icon">👤</span>
                        <span>Profil</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <span class="item-icon">⚙️</span>
                        <span>Pengaturan</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?= isset($logout_path) ? $logout_path : '../logout.php' ?>" class="dropdown-item logout-item">
                        <span class="item-icon">🚪</span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Search Overlay>
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
