<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

// Set page title
$page_title = "Catat Stok Keluar - Stock Control Gudang";

// Include the header template
require_once '../../templates/header.php';
require_once '../../config/database.php';

// Get products for dropdown
$produk_query = "SELECT id_produk, nama_produk FROM produk ORDER BY nama_produk";
$produk_result = $koneksi->query($produk_query);

// Get locations for dropdown
$lokasi_query = "SELECT id_lokasi, nama_lokasi FROM lokasi_gudang ORDER BY nama_lokasi";
$lokasi_result = $koneksi->query($lokasi_query);

// Get customers for dropdown
$pelanggan_query = "SELECT id_pelanggan, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan";
$pelanggan_result = $koneksi->query($pelanggan_query);
?>

<div class="container">
    <h2>📤 Catat Stok Keluar Baru</h2>
    
    <div class="nav-links">
        <a href="index.php" class="btn btn-secondary">
            ← Kembali ke Riwayat Stok Keluar
        </a>
    </div>

    <div class="form-container">
        <form action="../../proses/simpan_stok_keluar.php" method="post" id="stockOutForm">
            <div class="form-grid">
                <div class="form-field">
                    <select name="id_produk" id="id_produk" required>
                        <option value="">Pilih Produk</option>
                        <?php while ($produk = $produk_result->fetch_assoc()): ?>
                            <option value="<?= $produk['id_produk']; ?>">
                                <?= htmlspecialchars($produk['nama_produk']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <label for="id_produk">Produk</label>
                </div>

                <div class="form-field">
                    <select name="id_lokasi" id="id_lokasi" required>
                        <option value="">Pilih Lokasi</option>
                        <?php while ($lokasi = $lokasi_result->fetch_assoc()): ?>
                            <option value="<?= $lokasi['id_lokasi']; ?>">
                                <?= htmlspecialchars($lokasi['nama_lokasi']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <label for="id_lokasi">Lokasi Gudang</label>
                </div>

                <div class="form-field">
                    <input type="number" name="jumlah_keluar" id="jumlah_keluar" required min="1" step="1" placeholder=" ">
                    <label for="jumlah_keluar">Jumlah Keluar (unit)</label>
                    <div class="stock-info" id="stockInfo" style="display: none;">
                        <small class="stock-available">Stok tersedia: <span id="availableStock">0</span> unit</small>
                    </div>
                </div>

                <div class="form-field">
                    <select name="id_pelanggan" id="id_pelanggan">
                        <option value="">Pilih Pelanggan (Opsional)</option>
                        <?php while ($pelanggan = $pelanggan_result->fetch_assoc()): ?>
                            <option value="<?= $pelanggan['id_pelanggan']; ?>">
                                <?= htmlspecialchars($pelanggan['nama_pelanggan']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <label for="id_pelanggan">Pelanggan</label>
                </div>

                <div class="form-field">
                    <select name="tipe_keluar" id="tipe_keluar" required>
                        <option value="">Pilih Tipe Keluar</option>
                        <option value="Penjualan">🛒 Penjualan</option>
                        <option value="Transfer">🔄 Transfer</option>
                        <option value="Rusak">⚠️ Rusak</option>
                        <option value="Lain-lain">📋 Lain-lain</option>
                    </select>
                    <label for="tipe_keluar">Tipe Keluar</label>
                </div>

                <div class="form-field">
                    <input type="text" name="nomor_referensi" id="nomor_referensi" required placeholder=" ">
                    <label for="nomor_referensi">Nomor Referensi</label>
                </div>
            </div>

            <div class="form-field" style="grid-column: 1 / -1;">
                <textarea name="keterangan" id="keterangan" placeholder=" "></textarea>
                <label for="keterangan">Keterangan (Opsional)</label>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">
                    ❌ Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    💾 Simpan Stok Keluar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<!-- Stock Check Modal -->
<div class="confirmation-dialog" id="stockCheckModal">
    <div class="confirmation-content">
        <div class="confirmation-icon" id="modalIcon">⚠️</div>
        <h3 class="confirmation-title" id="modalTitle">Periksa Stok</h3>
        <div class="confirmation-message">
            <p id="modalMessage">Memeriksa ketersediaan stok...</p>
            <div id="stockDetails" class="stock-details" style="display: none;"></div>
        </div>
        <div class="confirmation-actions" id="modalActions">
            <button type="button" class="btn btn-secondary" onclick="closeStockModal()">
                ❌ Tutup
            </button>
            <button type="button" class="btn btn-primary" id="confirmSubmit" onclick="confirmStockOut()" style="display: none;">
                💾 Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
let currentStockData = null;

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('stockOutForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Add floating particles to form container
    const formContainer = document.querySelector('.form-container');
    if (formContainer) {
        createFloatingParticles(formContainer);
    }
    
    // Enhanced form validation with stock check
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form first
        if (!validateForm()) {
            return;
        }
        
        // Check stock availability
        checkStockAvailability();
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
        input.addEventListener('change', clearFieldError);
    });
    
    // Stock checking when product/location changes
    const produkSelect = document.getElementById('id_produk');
    const lokasiSelect = document.getElementById('id_lokasi');
    const jumlahInput = document.getElementById('jumlah_keluar');
    
    [produkSelect, lokasiSelect].forEach(select => {
        select.addEventListener('change', updateStockInfo);
    });
    
    jumlahInput.addEventListener('input', updateStockInfo);
    
    // Auto-generate reference number suggestion
    const tipeKeluar = document.getElementById('tipe_keluar');
    const nomorReferensi = document.getElementById('nomor_referensi');
    
    tipeKeluar.addEventListener('change', function() {
        if (!nomorReferensi.value) {
            const suggestion = generateReferenceNumber(this.value);
            if (suggestion) {
                showReferenceSuggestion(suggestion);
            }
        }
    });
    
    // Enhanced type selection with visual feedback
    tipeKeluar.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const emoji = selectedOption.text.split(' ')[0];
        
        // Add visual feedback based on type
        const formContainer = document.querySelector('.form-container');
        formContainer.classList.remove('type-penjualan', 'type-transfer', 'type-rusak', 'type-lainlain');
        
        switch(this.value) {
            case 'Penjualan':
                formContainer.classList.add('type-penjualan');
                showToast('🛒 Mode Penjualan - Pastikan data pelanggan terisi', 'info');
                break;
            case 'Transfer':
                formContainer.classList.add('type-transfer');
                showToast('🔄 Mode Transfer - Periksa lokasi tujuan', 'info');
                break;
            case 'Rusak':
                formContainer.classList.add('type-rusak');
                showToast('⚠️ Mode Barang Rusak - Dokumentasikan kerusakan', 'warning');
                break;
            case 'Lain-lain':
                formContainer.classList.add('type-lainlain');
                showToast('📋 Mode Lain-lain - Jelaskan di keterangan', 'info');
                break;
        }
    });
});

async function updateStockInfo() {
    const produkId = document.getElementById('id_produk').value;
    const lokasiId = document.getElementById('id_lokasi').value;
    const jumlahKeluar = document.getElementById('jumlah_keluar').value;
    const stockInfo = document.getElementById('stockInfo');
    const availableStock = document.getElementById('availableStock');
    
    if (!produkId || !lokasiId) {
        stockInfo.style.display = 'none';
        return;
    }
    
    try {
        const response = await fetch('check_stock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_produk=${produkId}&id_lokasi=${lokasiId}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentStockData = data;
            availableStock.textContent = data.available_stock;
            stockInfo.style.display = 'block';
            
            // Update styling based on stock availability
            if (jumlahKeluar && parseInt(jumlahKeluar) > data.available_stock) {
                stockInfo.classList.add('insufficient');
                stockInfo.classList.remove('sufficient');
            } else {
                stockInfo.classList.add('sufficient');
                stockInfo.classList.remove('insufficient');
            }
        } else {
            stockInfo.style.display = 'none';
            currentStockData = null;
        }
    } catch (error) {
        console.error('Error checking stock:', error);
        stockInfo.style.display = 'none';
    }
}

async function checkStockAvailability() {
    const form = document.getElementById('stockOutForm');
    const formData = new FormData(form);
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Show loading
    loadingOverlay.classList.add('show');
    
    try {
        const response = await fetch('check_stock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_produk=${formData.get('id_produk')}&id_lokasi=${formData.get('id_lokasi')}&jumlah_keluar=${formData.get('jumlah_keluar')}`
        });
        
        const data = await response.json();
        
        // Hide loading
        loadingOverlay.classList.remove('show');
        
        if (data.success) {
            if (data.sufficient) {
                // Stock is sufficient, show confirmation
                showStockConfirmation(data);
            } else {
                // Stock is insufficient, show error
                showStockError(data);
            }
        } else {
            // No stock found
            showStockError({
                message: "Stok tidak tersedia di lokasi ini.",
                available_stock: 0,
                requested_stock: formData.get('jumlah_keluar'),
                product_name: document.getElementById('id_produk').options[document.getElementById('id_produk').selectedIndex].text,
                location_name: document.getElementById('id_lokasi').options[document.getElementById('id_lokasi').selectedIndex].text
            });
        }
    } catch (error) {
        loadingOverlay.classList.remove('show');
        showToast('❌ Terjadi kesalahan saat memeriksa stok', 'error');
        console.error('Error:', error);
    }
}

function showStockError(data) {
    const modal = document.getElementById('stockCheckModal');
    const icon = document.getElementById('modalIcon');
    const title = document.getElementById('modalTitle');
    const message = document.getElementById('modalMessage');
    const details = document.getElementById('stockDetails');
    const confirmBtn = document.getElementById('confirmSubmit');
    
    // Set error styling
    icon.textContent = '❌';
    title.textContent = 'Stok Tidak Mencukupi';
    message.innerHTML = '<strong style="color: var(--md-sys-color-error);">Jumlah keluar melebihi stok tersedia.</strong>';
    
    // Show stock details
    details.innerHTML = `
        <div class="stock-item">
            <span class="stock-label">Produk:</span>
            <span class="stock-value">${data.product_name || 'N/A'}</span>
        </div>
        <div class="stock-item">
            <span class="stock-label">Lokasi:</span>
            <span class="stock-value">${data.location_name || 'N/A'}</span>
        </div>
        <div class="stock-item">
            <span class="stock-label">Stok Tersedia:</span>
            <span class="stock-value insufficient">${data.available_stock || 0} unit</span>
        </div>
        <div class="stock-item">
            <span class="stock-label">Jumlah Diminta:</span>
            <span class="stock-value insufficient">${data.requested_stock || 0} unit</span>
        </div>
        <div class="stock-item">
            <span class="stock-label">Kekurangan:</span>
            <span class="stock-value insufficient">${(data.requested_stock || 0) - (data.available_stock || 0)} unit</span>
        </div>
    `;
    details.style.display = 'block';
    
    // Hide confirm button
    confirmBtn.style.display = 'none';
    
    // Show modal
    modal.classList.add('show');
    
    // Add escape key listener
    document.addEventListener('keydown', handleEscapeKey);
}

function showStockConfirmation(data) {
    const modal = document.getElementById('stockCheckModal');
    const icon = document.getElementById('modalIcon');
    const title = document.getElementById('modalTitle');
    const message = document.getElementById('modalMessage');
    const details = document.getElementById('stockDetails');
    const confirmBtn = document.getElementById('confirmSubmit');
    
    // Set success styling
    icon.textContent = '✅';
    title.textContent = 'Konfirmasi Stok Keluar';
    message.innerHTML = '<strong style="color: #4caf50;">Stok mencukupi. Lanjutkan proses stok keluar?</strong>';
    
    // Show stock details
    details.innerHTML = `
        <div class="stock-item">
            <span class="stock-label">Produk:</span>
            <span class="stock-value">${data.product_name}</span>
        </div>
        <div class="stock-item">
            <span class="stock-label">Lokasi:</span>
            <span class="stock-value">${data.location_name}</span>
        </div>
        <div class="stock-item">
            <span class="stock-label">Stok Tersedia:</span>
            <span class="stock-value sufficient">${data.available_stock} unit</span>
        </div>
        <div class="stock-item">
            <span class="stock-label">Jumlah Keluar:</span>
            <span class="stock-value">${data.requested_stock} unit</span>
        </div>
        <div class="stock-item">
            <span class="stock-label">Sisa Stok:</span>
            <span class="stock-value sufficient">${data.available_stock - data.requested_stock} unit</span>
        </div>
    `;
    details.style.display = 'block';
    
    // Show confirm button
    confirmBtn.style.display = 'inline-flex';
    
    // Show modal
    modal.classList.add('show');
    
    // Add escape key listener
    document.addEventListener('keydown', handleEscapeKey);
}

function closeStockModal() {
    const modal = document.getElementById('stockCheckModal');
    modal.classList.remove('show');
    
    // Remove escape key listener
    document.removeEventListener('keydown', handleEscapeKey);
}

function handleEscapeKey(e) {
    if (e.key === 'Escape') {
        closeStockModal();
    }
}

function confirmStockOut() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    const modal = document.getElementById('stockCheckModal');
    
    // Close modal and show loading
    modal.classList.remove('show');
    loadingOverlay.classList.add('show');
    
    // Show success message
    showToast('💾 Menyimpan stok keluar...', 'info');
    
    // Submit form after short delay
    setTimeout(() => {
        document.getElementById('stockOutForm').submit();
    }, 1500);
}

function generateReferenceNumber(tipeKeluar) {
    if (!tipeKeluar) return '';
    
    const today = new Date();
    const year = today.getFullYear().toString().substr(-2);
    const month = (today.getMonth() + 1).toString().padStart(2, '0');
    const day = today.getDate().toString().padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    
    let prefix = 'SK';
    switch(tipeKeluar) {
        case 'Penjualan': prefix = 'PJ'; break;
        case 'Transfer': prefix = 'TF'; break;
        case 'Rusak': prefix = 'RK'; break;
        case 'Lain-lain': prefix = 'LL'; break;
    }
    
    return `${prefix}${year}${month}${day}${random}`;
}

function generateReferenceFromProduct(productName, tipeKeluar) {
    if (!productName) return '';
    
    const today = new Date();
    const year = today.getFullYear().toString().substr(-2);
    const month = (today.getMonth() + 1).toString().padStart(2, '0');
    const day = today.getDate().toString().padStart(2, '0');
    
    // Generate code from first letters of product name
    const words = productName.trim().split(' ');
    let productCode = '';
    
    words.forEach(word => {
        if (word.length > 0) {
            productCode += word.charAt(0).toUpperCase();
        }
    });
    
    if (productCode.length > 2) {
        productCode = productCode.substr(0, 2);
    }
    
    let prefix = 'SK';
    if (tipeKeluar) {
        switch(tipeKeluar) {
            case 'Penjualan': prefix = 'PJ'; break;
            case 'Transfer': prefix = 'TF'; break;
            case 'Rusak': prefix = 'RK'; break;
            case 'Lain-lain': prefix = 'LL'; break;
        }
    }
    
    const random = Math.floor(Math.random() * 100).toString().padStart(2, '0');
    
    return `${prefix}${productCode}${year}${month}${day}${random}`;
}

function showReferenceSuggestion(suggestion) {
    // Remove existing suggestion
    const existingSuggestion = document.querySelector('.reference-suggestion');
    if (existingSuggestion) {
        existingSuggestion.remove();
    }
    
    const suggestionDiv = document.createElement('div');
    suggestionDiv.className = 'reference-suggestion';
    suggestionDiv.innerHTML = `
        <small style="color: var(--md-sys-color-primary); font-weight: 500; cursor: pointer;" onclick="useReferenceSuggestion('${suggestion}')">
            💡 Saran referensi: ${suggestion} (klik untuk gunakan)
        </small>
    `;
    suggestionDiv.style.marginTop = '8px';
    
    const nomorReferensi = document.getElementById('nomor_referensi').parentNode;
    nomorReferensi.appendChild(suggestionDiv);
}

function useReferenceSuggestion(reference) {
    document.getElementById('nomor_referensi').value = reference;
    document.querySelector('.reference-suggestion').remove();
    showToast('💡 Nomor referensi telah diisi otomatis', 'info');
}

function validateForm() {
    const form = document.getElementById('stockOutForm');
    const formData = new FormData(form);
    let isValid = true;
    
    // Required field validation
    const requiredFields = ['id_produk', 'id_lokasi', 'jumlah_keluar', 'tipe_keluar', 'nomor_referensi'];
    
    requiredFields.forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        const value = formData.get(fieldName);
        
        if (!value || value.trim() === '') {
            showFieldError(field, 'Field ini wajib diisi');
            isValid = false;
        }
    });
    
    // Quantity validation
    const jumlahKeluar = formData.get('jumlah_keluar');
    if (jumlahKeluar && (parseFloat(jumlahKeluar) <= 0 || !Number.isInteger(parseFloat(jumlahKeluar)))) {
        showFieldError(form.querySelector('[name="jumlah_keluar"]'), 'Jumlah harus berupa bilangan bulat positif');
        isValid = false;
    }
    
    // Reference number validation
    const nomorRef = formData.get('nomor_referensi');
    if (nomorRef && nomorRef.length < 3) {
        showFieldError(form.querySelector('[name="nomor_referensi"]'), 'Nomor referensi minimal 3 karakter');
        isValid = false;
    }
    
    // Special validation for sales type
    const tipeKeluar = formData.get('tipe_keluar');
    const pelanggan = formData.get('id_pelanggan');
    if (tipeKeluar === 'Penjualan' && !pelanggan) {
        showFieldError(form.querySelector('[name="id_pelanggan"]'), 'Pelanggan wajib diisi untuk penjualan');
        isValid = false;
    }
    
    return isValid;
}

function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'Field ini wajib diisi');
        return false;
    }
    
    if (field.type === 'number' && value) {
        if (parseFloat(value) <= 0) {
            showFieldError(field, 'Nilai harus lebih dari 0');
            return false;
        }
        if (field.name === 'jumlah_keluar' && !Number.isInteger(parseFloat(value))) {
            showFieldError(field, 'Jumlah harus berupa bilangan bulat');
            return false;
        }
    }
    
    if (field.name === 'nomor_referensi' && value && value.length < 3) {
        showFieldError(field, 'Nomor referensi minimal 3 karakter');
        return false;
    }
    
    // Special validation for sales
    if (field.name === 'id_pelanggan') {
        const tipeKeluar = document.getElementById('tipe_keluar').value;
        if (tipeKeluar === 'Penjualan' && !value) {
            showFieldError(field, 'Pelanggan wajib diisi untuk penjualan');
            return false;
        }
    }
    
    clearFieldError(field);
    return true;
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = 'var(--md-sys-color-error)';
    field.style.boxShadow = '0 0 0 2px rgba(186, 26, 26, 0.2)';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.color = 'var(--md-sys-color-error)';
    errorDiv.style.fontSize = '12px';
    errorDiv.style.marginTop = '4px';
    errorDiv.style.fontWeight = '500';
    
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
    
    field.style.borderColor = '';
    field.style.boxShadow = '';
}

function createFloatingParticles(container) {
    for (let i = 0; i < 4; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.position = 'absolute';
        particle.style.width = '2px';
        particle.style.height = '2px';
        particle.style.background = 'rgba(255, 255, 255, 0.6)';
        particle.style.borderRadius = '50%';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = 8 + Math.random() * 4 + 's';
        particle.style.animation = 'particle-float 8s linear infinite';
        particle.style.pointerEvents = 'none';
        container.appendChild(particle);
    }
}

function showToast(message, type) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    let bgColor = 'rgba(76, 175, 80, 0.9)';
    switch(type) {
        case 'success': bgColor = 'rgba(76, 175, 80, 0.9)'; break;
        case 'warning': bgColor = 'rgba(255, 152, 0, 0.9)'; break;
        case 'error': bgColor = 'rgba(244, 67, 54, 0.9)'; break;
        case 'info': bgColor = 'rgba(33, 150, 243, 0.9)'; break;
    }
    
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${bgColor};
        color: white;
        border-radius: 8px;
        font-weight: 500;
        z-index: 10000;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
        word-wrap: break-word;
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 4 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 4000);
}

// Close modal when clicking outside
document.getElementById('stockCheckModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStockModal();
    }
});
</script>

<style>
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

/* Form Container with Glass Effect */
.form-container {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 32px;
    margin: 20px 0;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 
                0 2px 8px rgba(0, 0, 0, 0.05), 
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(255, 255, 255, 0.1);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

[data-theme="dark"] .form-container {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 
                0 2px 8px rgba(0, 0, 0, 0.2), 
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                inset 0 -1px 0 rgba(255, 255, 255, 0.05);
}

/* Form Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}

/* Enhanced Form Fields */
.form-field {
    position: relative;
    margin-bottom: 24px;
}

.form-field input,
.form-field textarea,
.form-field select {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: var(--md-sys-color-on-surface);
    font-family: "Roboto", sans-serif;
    font-size: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05), 
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .form-field input,
[data-theme="dark"] .form-field textarea,
[data-theme="dark"] .form-field select {
    background: rgba(255, 255, 255, 0.03);
    border: 2px solid rgba(255, 255, 255, 0.15);
}

.form-field input:focus,
.form-field textarea:focus,
.form-field select:focus {
    outline: none;
    border-color: var(--md-sys-color-primary);
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 0 0 4px rgba(103, 80, 164, 0.2), 
                0 4px 12px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.form-field label {
    position: absolute;
    left: 20px;
    top: 16px;
    color: var(--md-sys-color-on-surface-variant);
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    pointer-events: none;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
    padding: 0 8px;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

[data-theme="dark"] .form-field label {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.5) 100%);
}

.form-field input:focus + label,
.form-field textarea:focus + label,
.form-field select:focus + label,
.form-field input:not(:placeholder-shown) + label,
.form-field textarea:not(:placeholder-shown) + label,
.form-field select:not([value=""]) + label {
    top: -12px;
    left: 16px;
    font-size: 12px;
    color: var(--md-sys-color-primary);
    font-weight: 600;
    transform: scale(0.9);
}

.form-field textarea {
    min-height: 120px;
    resize: vertical;
}

.form-field select {
    cursor: pointer;
}

.form-field select option {
    background: var(--md-sys-color-surface);
    color: var(--md-sys-color-on-surface);
    padding: 12px;
}

/* Stock Info */
.stock-info {
    margin-top: 8px;
    padding: 8px 12px;
    background: rgba(76, 175, 80, 0.1);
    border: 1px solid rgba(76, 175, 80, 0.3);
    border-radius: 8px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.stock-available {
    color: #4caf50;
    font-weight: 600;
    font-size: 12px;
}

.stock-info.insufficient {
    background: rgba(244, 67, 54, 0.1);
    border-color: rgba(244, 67, 54, 0.3);
}

.stock-info.insufficient .stock-available {
    color: #f44336;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    margin-top: 32px;
    flex-wrap: wrap;
}

.form-actions .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 28px;
    border: none;
    border-radius: var(--md-sys-shape-corner-full);
    font-family: "Roboto", sans-serif;
    font-size: 16px;
    font-weight: 600;
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
    min-width: 140px;
}

.form-actions .btn-secondary {
    background: linear-gradient(135deg, rgba(98, 91, 113, 0.15) 0%, rgba(98, 91, 113, 0.08) 100%);
    color: var(--md-sys-color-on-secondary-container);
}

.form-actions .btn-primary {
    background: linear-gradient(135deg, var(--md-sys-color-primary) 0%, rgba(103, 80, 164, 0.9) 100%);
    color: var(--md-sys-color-on-primary);
}

.form-actions .btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15), 
                0 4px 12px rgba(0, 0, 0, 0.1), 
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
}

.form-actions .btn-secondary:hover {
    background: linear-gradient(135deg, rgba(98, 91, 113, 0.25) 0%, rgba(98, 91, 113, 0.15) 100%);
}

.form-actions .btn-primary:hover {
    background: linear-gradient(135deg, rgba(103, 80, 164, 1) 0%, rgba(103, 80, 164, 0.95) 100%);
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.loading-overlay.show {
    opacity: 1;
    visibility: visible;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid var(--md-sys-color-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Stock Details */
.stock-details {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 16px;
    margin-top: 16px;
    text-align: left;
}

.stock-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.stock-item:last-child {
    border-bottom: none;
}

.stock-label {
    font-weight: 500;
    color: var(--md-sys-color-on-surface-variant);
}

.stock-value {
    font-weight: 600;
    color: var(--md-sys-color-on-surface);
}

.stock-value.insufficient {
    color: var(--md-sys-color-error);
}

.stock-value.sufficient {
    color: #4caf50;
}

/* Floating Particles Animation */
.form-container .particle {
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

/* Responsive Design */
@media (max-width: 768px) {
    .form-container {
        padding: 20px;
        margin: 16px 0;
        border-radius: 16px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .form-field input,
    .form-field textarea,
    .form-field select {
        padding: 14px 16px;
        font-size: 16px;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .nav-links {
        flex-direction: column;
        align-items: stretch;
    }
    
    .nav-links .btn {
        width: 100%;
        justify-content: center;
    }
    
    .confirmation-content {
        padding: 24px;
        margin: 20px;
    }
    
    .confirmation-actions {
        flex-direction: column;
    }
    
    .confirmation-actions .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-field input,
    .form-field textarea,
    .form-field select {
        font-size: 16px; /* Prevent zoom on iOS */
    }
}
</style>

<?php
require_once '../../templates/footer.php';
?>
