</main>
    
<?php
// Tentukan path JS yang benar dengan urutan pengecekan yang tepat
$js_path = '';
$nav_js_path = '';
$self_path = $_SERVER['PHP_SELF'];

if (strpos($self_path, '/pages/supplier/') !== false || 
    strpos($self_path, '/pages/produk/') !== false ||
    strpos($self_path, '/pages/lokasi/') !== false ||
    strpos($self_path, '/pages/pelanggan/') !== false ||
    strpos($self_path, '/pages/stok_masuk/') !== false ||
    strpos($self_path, '/pages/stok_keluar/') !== false ||
    strpos($self_path, '/pages/stok_saat_ini/') !== false) {
    // 1. Cek folder yang lebih dalam DULU
    $js_path = '../../assets/js/material3-interactions.js';
    $nav_js_path = '../../assets/js/navigation.js';
} elseif (strpos($self_path, '/pages/') !== false) {
    // 2. BARU cek folder /pages/
    $js_path = '../assets/js/material3-interactions.js';
    $nav_js_path = '../assets/js/navigation.js';
} else {
    // 3. Jika di root
    $js_path = 'assets/js/material3-interactions.js';
    $nav_js_path = 'assets/js/navigation.js';
}
?>
    <!-- Navigation JavaScript -->
    <script src="<?php echo $nav_js_path; ?>"></script>
    
    <!-- Material Design 3 JavaScript -->
    <script src="<?php echo $js_path; ?>"></script>
    
    <!-- Additional page-specific scripts can be added here -->
    <?php if (isset($additional_js)): ?>
        <?= $additional_js ?>
    <?php endif; ?>
    
    <script>
        // Initialize navigation and page functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add any global initialization here
            console.log('Navigation system initialized');
            
            // Show welcome notification for first-time visitors
            if (sessionStorage.getItem('welcomed') !== 'true') {
                setTimeout(() => {
                    if (typeof showNotification === 'function') {
                        showNotification('Selamat datang di Stock Control Gudang! 🎉', 'success');
                    }
                    sessionStorage.setItem('welcomed', 'true');
                }, 1000);
            }
        });

        // Announce page changes to screen readers
        if (document.title) {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.style.position = 'absolute';
            announcement.style.left = '-10000px';
            announcement.style.width = '1px';
            announcement.style.height = '1px';
            announcement.style.overflow = 'hidden';
            announcement.textContent = `Page loaded: ${document.title}`;
            document.body.appendChild(announcement);
        }
        
        // Add high contrast mode toggle
        function toggleHighContrast() {
            document.body.classList.toggle('high-contrast');
            const isHighContrast = document.body.classList.contains('high-contrast');
            localStorage.setItem('high-contrast', isHighContrast);
            
            // Announce to screen readers
            const message = isHighContrast ? 'High contrast mode enabled' : 'High contrast mode disabled';
            if (window.MaterialDesign && window.MaterialDesign.showToast) {
                window.MaterialDesign.showToast(message, 'info');
            } else if (typeof showNotification === 'function') {
                showNotification(message, 'info');
            }
        }
        
        // Restore high contrast preference
        if (localStorage.getItem('high-contrast') === 'true') {
            document.body.classList.add('high-contrast');
        }
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // High contrast toggle (Alt + H)
            if (e.altKey && e.key === 'h') {
                e.preventDefault();
                toggleHighContrast();
            }
            
            // Theme toggle (Alt + T)
            if (e.altKey && e.key === 't') {
                e.preventDefault();
                if (window.MaterialDesign && window.MaterialDesign.toggleTheme) {
                    window.MaterialDesign.toggleTheme();
                }
            }

            // Open navigation (Alt + M)
            if (e.altKey && e.key === 'm') {
                e.preventDefault();
                if (typeof toggleOffcanvas === 'function') {
                    toggleOffcanvas();
                }
            }

            // Open search (Alt + S)
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                if (typeof openSearch === 'function') {
                    openSearch();
                }
            }
        });
        
        // Reduce motion for users who prefer it
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--animation-duration', '0.01s');
        }
    </script>
    
    <style>
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: var(--md-sys-color-primary, #6750a4);
            color: var(--md-sys-color-on-primary, #ffffff);
            padding: 8px;
            text-decoration: none;
            border-radius: 4px;
            z-index: 1000;
            transition: top 0.2s ease;
        }
        
        .skip-link:focus {
            top: 6px;
        }
        
        .high-contrast {
            filter: contrast(150%) brightness(120%);
        }
        
        .high-contrast .gradient-background {
            opacity: 0.1 !important;
        }

        /* Main content area adjustment for navbar */
        .main-content {
            padding-top: 0; /* Navbar already adds body padding-top */
        }

        /* Theme toggle positioning adjustment */
        .theme-toggle {
            top: 80px; /* Move below navbar */
            z-index: 999; /* Below navbar but above content */
        }
    </style>
</body>
</html>
