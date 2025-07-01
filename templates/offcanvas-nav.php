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
                    <a href="<?= isset($dashboard_path) ? $dashboard_path : '../dashboard.php' ?>" class="nav-link">
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
                    <a href="<?= isset($produk_path) ? $produk_path : '../produk/index.php' ?>" class="nav-link">
                        <span class="nav-icon">📦</span>
                        <span class="nav-text">Data Produk</span>
                        <span class="nav-arrow">→</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= isset($lokasi_path) ? $lokasi_path : '../lokasi/index.php' ?>" class="nav-link">
                        <span class="nav-icon">📍</span>
                        <span class="nav-text">Lokasi Gudang</span>
                        <span class="nav-arrow">→</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= isset($supplier_path) ? $supplier_path : '../supplier/index.php' ?>" class="nav-link">
                        <span class="nav-icon">🏢</span>
                        <span class="nav-text">Data Supplier</span>
                        <span class="nav-arrow">→</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= isset($pelanggan_path) ? $pelanggan_path : '../pelanggan/index.php' ?>" class="nav-link">
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
                    <a href="<?= isset($stok_masuk_path) ? $stok_masuk_path : '../stok_masuk/index.php' ?>" class="nav-link">
                        <span class="nav-icon">📦</span>
                        <span class="nav-text">Stok Masuk</span>
                        <span class="nav-badge new">New</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= isset($stok_keluar_path) ? $stok_keluar_path : '../stok_keluar/index.php' ?>" class="nav-link">
                        <span class="nav-icon">📤</span>
                        <span class="nav-text">Stok Keluar</span>
                        <span class="nav-arrow">→</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= isset($stok_saat_ini_path) ? $stok_saat_ini_path : '../stok_saat_ini/index.php' ?>" class="nav-link">
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
                <a href="<?= isset($tambah_stok_masuk_path) ? $tambah_stok_masuk_path : '../stok_masuk/tambah.php' ?>" class="quick-action-card">
                    <div class="action-icon">📦</div>
                    <div class="action-text">
                        <strong>Tambah Stok</strong>
                        <small>Stok Masuk Baru</small>
                    </div>
                </a>
                <a href="<?= isset($catat_stok_keluar_path) ? $catat_stok_keluar_path : '../stok_keluar/catat_stok.php' ?>" class="quick-action-card">
                    <div class="action-icon">📤</div>
                    <div class="action-text">
                        <strong>Catat Keluar</strong>
                        <small>Stok Keluar</small>
                    </div>
                </a>
                <a href="<?= isset($tambah_produk_path) ? $tambah_produk_path : '../produk/tambah.php' ?>" class="quick-action-card">
                    <div class="action-icon">➕</div>
                    <div class="action-text">
                        <strong>Produk Baru</strong>
                        <small>Tambah Produk</small>
                    </div>
                </a>
                <a href="<?= isset($tambah_supplier_path) ? $tambah_supplier_path : '../supplier/tambah.php' ?>" class="quick-action-card">
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
                    <span class="info-value">v0.01a dev</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Terakhir Diperbarui</span>
                    <span class="info-value"><?= date('d/m/Y H:i') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas Footer -->
    <div class="offcanvas-footer">
        <div class="footer-user">
            <div class="user-avatar-small">
                <span><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></span>
            </div>
            <div class="user-details">
                <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                <small>Masuk saat <?= date('H:i') ?></small>
            </div>
        </div>
        <a href="<?= isset($logout_path) ? $logout_path : '../logout.php' ?>" class="logout-btn">
            <span>🚪</span>
        </a>
    </div>
</nav>
