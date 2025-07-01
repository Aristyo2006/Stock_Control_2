<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

// Set page title
$page_title = "Tambah Stok Masuk - Stock Control Gudang";

// Include the header template
require_once '../../templates/header.php';
require_once '../../config/database.php';

// Get products for dropdown
$produk_query = "SELECT id_produk, nama_produk FROM produk ORDER BY nama_produk";
$produk_result = $koneksi->query($produk_query);

// Get locations for dropdown
$lokasi_query = "SELECT id_lokasi, nama_lokasi FROM lokasi_gudang ORDER BY nama_lokasi";
$lokasi_result = $koneksi->query($lokasi_query);

// Get suppliers for dropdown
$supplier_query = "SELECT id_supplier, nama_supplier FROM supplier ORDER BY nama_supplier";
$supplier_result = $koneksi->query($supplier_query);
?>

<div class="container">
    <h2>📦 Tambah Stok Masuk Baru</h2>
    
    <div class="nav-links">
        <a href="index.php" class="btn btn-secondary">
            ← Kembali ke Data Stok Masuk
        </a>
    </div>

    <div class="form-container">
        <form action="../../proses/simpan_stok_masuk.php" method="post" id="stockForm">
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
                    <input type="number" name="jumlah_masuk" id="jumlah_masuk" required min="1" step="1" placeholder=" ">
                    <label for="jumlah_masuk">Jumlah Masuk (unit)</label>
                </div>

                <div class="form-field">
                    <select name="id_supplier" id="id_supplier">
                        <option value="">Pilih Supplier (Opsional)</option>
                        <?php while ($supplier = $supplier_result->fetch_assoc()): ?>
                            <option value="<?= $supplier['id_supplier']; ?>">
                                <?= htmlspecialchars($supplier['nama_supplier']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <label for="id_supplier">Supplier</label>
                </div>

                <div class="form-field">
                    <input type="date" name="tanggal_masuk" id="tanggal_masuk" required value="<?= date('Y-m-d'); ?>" placeholder=" ">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
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
                    💾 Simpan Stok Masuk
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('stockForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Add floating particles to form container
    const formContainer = document.querySelector('.form-container');
    if (formContainer) {
        createFloatingParticles(formContainer);
    }
    
    // Enhanced form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading overlay
        loadingOverlay.classList.add('show');
        
        // Validate form
        if (validateForm()) {
            // Show success message and submit
            setTimeout(() => {
                showToast('✅ Stok masuk berhasil ditambahkan!', 'success');
                this.submit();
            }, 1000);
        } else {
            loadingOverlay.classList.remove('show');
        }
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
        input.addEventListener('change', clearFieldError);
    });
    
    // Auto-generate reference number suggestion
    const tanggalMasuk = document.getElementById('tanggal_masuk');
    const nomorReferensi = document.getElementById('nomor_referensi');
    
    tanggalMasuk.addEventListener('change', function() {
        if (!nomorReferensi.value) {
            const suggestion = generateReferenceNumber(this.value);
            if (suggestion) {
                showReferenceSuggestion(suggestion);
            }
        }
    });
    
    // Product selection enhancement
    const produkSelect = document.getElementById('id_produk');
    produkSelect.addEventListener('change', function() {
        if (this.value && !nomorReferensi.value) {
            const selectedOption = this.options[this.selectedIndex];
            const productName = selectedOption.text;
            const suggestion = generateReferenceFromProduct(productName, tanggalMasuk.value);
            if (suggestion) {
                showReferenceSuggestion(suggestion);
            }
        }
    });
});

function generateReferenceNumber(tanggal) {
    if (!tanggal) return '';
    
    const date = new Date(tanggal);
    const year = date.getFullYear().toString().substr(-2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    
    return `SM${year}${month}${day}${random}`;
}

function generateReferenceFromProduct(productName, tanggal) {
    if (!productName || !tanggal) return '';
    
    const date = new Date(tanggal);
    const year = date.getFullYear().toString().substr(-2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    
    // Generate code from first letters of product name
    const words = productName.trim().split(' ');
    let productCode = '';
    
    words.forEach(word => {
        if (word.length > 0) {
            productCode += word.charAt(0).toUpperCase();
        }
    });
    
    if (productCode.length > 3) {
        productCode = productCode.substr(0, 3);
    }
    
    const random = Math.floor(Math.random() * 100).toString().padStart(2, '0');
    
    return `SM${productCode}${year}${month}${day}${random}`;
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
    const form = document.getElementById('stockForm');
    const formData = new FormData(form);
    let isValid = true;
    
    // Required field validation
    const requiredFields = ['id_produk', 'id_lokasi', 'jumlah_masuk', 'tanggal_masuk', 'nomor_referensi'];
    
    requiredFields.forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        const value = formData.get(fieldName);
        
        if (!value || value.trim() === '') {
            showFieldError(field, 'Field ini wajib diisi');
            isValid = false;
        }
    });
    
    // Quantity validation
    const jumlahMasuk = formData.get('jumlah_masuk');
    if (jumlahMasuk && (parseFloat(jumlahMasuk) <= 0 || !Number.isInteger(parseFloat(jumlahMasuk)))) {
        showFieldError(form.querySelector('[name="jumlah_masuk"]'), 'Jumlah harus berupa bilangan bulat positif');
        isValid = false;
    }
    
    // Date validation
    const tanggalMasuk = formData.get('tanggal_masuk');
    if (tanggalMasuk) {
        const selectedDate = new Date(tanggalMasuk);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate > today) {
            showFieldError(form.querySelector('[name="tanggal_masuk"]'), 'Tanggal tidak boleh lebih dari hari ini');
            isValid = false;
        }
    }
    
    // Reference number validation
    const nomorRef = formData.get('nomor_referensi');
    if (nomorRef && nomorRef.length < 3) {
        showFieldError(form.querySelector('[name="nomor_referensi"]'), 'Nomor referensi minimal 3 karakter');
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
        if (field.name === 'jumlah_masuk' && !Number.isInteger(parseFloat(value))) {
            showFieldError(field, 'Jumlah harus berupa bilangan bulat');
            return false;
        }
    }
    
    if (field.type === 'date' && value) {
        const selectedDate = new Date(value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate > today) {
            showFieldError(field, 'Tanggal tidak boleh lebih dari hari ini');
            return false;
        }
    }
    
    if (field.name === 'nomor_referensi' && value && value.length < 3) {
        showFieldError(field, 'Nomor referensi minimal 3 karakter');
        return false;
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
    if (window.MaterialDesign && window.MaterialDesign.showToast) {
        window.MaterialDesign.showToast(message, type);
    }
}
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