<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

// Set page title
$page_title = "Tambah Supplier - Stock Control Gudang";

// Include the header template
require_once '../../templates/header.php';
?>

<div class="container">
    <h2>🏢 Tambah Supplier Baru</h2>
    
    <div class="nav-links">
        <a href="index.php" class="btn btn-secondary">
            ← Kembali ke Data Supplier
        </a>
    </div>

    <div class="form-container">
        <form action="../../proses/simpan_supplier.php" method="post" id="supplierForm">
            <div class="form-grid">
                <div class="form-field">
                    <input type="text" name="nama_supplier" id="nama_supplier" required placeholder=" ">
                    <label for="nama_supplier">Nama Supplier</label>
                </div>

                <div class="form-field">
                    <input type="text" name="kontak_person" id="kontak_person" placeholder=" ">
                    <label for="kontak_person">Kontak Person</label>
                </div>

                <div class="form-field">
                    <input type="tel" name="telepon" id="telepon" placeholder=" ">
                    <label for="telepon">Nomor Telepon</label>
                </div>

                <div class="form-field">
                    <input type="email" name="email" id="email" placeholder=" ">
                    <label for="email">Email</label>
                </div>
            </div>

            <div class="form-field" style="grid-column: 1 / -1;">
                <textarea name="alamat" id="alamat" placeholder=" "></textarea>
                <label for="alamat">Alamat Lengkap</label>
            </div>

            <!-- Supplier Info Preview -->
            <div class="supplier-preview" id="supplierPreview" style="display: none;">
                <h4>📋 Preview Supplier</h4>
                <div class="preview-grid">
                    <div class="preview-item">
                        <span class="preview-label">Nama Supplier</span>
                        <span class="preview-value" id="previewNama">-</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Kontak Person</span>
                        <span class="preview-value" id="previewKontak">-</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Telepon</span>
                        <span class="preview-value" id="previewTelepon">-</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Email</span>
                        <span class="preview-value" id="previewEmail">-</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">
                    ❌ Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    💾 Simpan Supplier
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
    const form = document.getElementById('supplierForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Add floating particles to form container
    const formContainer = document.querySelector('.form-container');
    if (formContainer) {
        createFloatingParticles(formContainer);
    }
    
    // Enhanced form validation
    form.addEventListener('submit', function(e) {
        // Don't prevent default - let form submit normally
        // Just validate before submission
        if (!validateForm()) {
            e.preventDefault();
            showToast('❌ Mohon periksa kembali data yang diisi', 'error');
            return false;
        }
        
        // Show loading overlay briefly
        loadingOverlay.classList.add('show');
        showToast('💾 Menyimpan data supplier...', 'info');
    });
    
    // Real-time validation and preview
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
        input.addEventListener('input', updatePreview);
    });
    
    // Email domain suggestions
    const emailField = document.getElementById('email');
    emailField.addEventListener('input', function() {
        const value = this.value;
        if (value.includes('@') && !value.includes('.')) {
            showEmailSuggestions(value);
        }
    });
});

function updatePreview() {
    const preview = document.getElementById('supplierPreview');
    const nama = document.getElementById('nama_supplier').value;
    const kontak = document.getElementById('kontak_person').value;
    const telepon = document.getElementById('telepon').value;
    const email = document.getElementById('email').value;
    
    // Show preview if any field has value
    if (nama || kontak || telepon || email) {
        preview.style.display = 'block';
        
        document.getElementById('previewNama').textContent = nama || '-';
        document.getElementById('previewKontak').textContent = kontak || '-';
        document.getElementById('previewTelepon').textContent = telepon || '-';
        document.getElementById('previewEmail').textContent = email || '-';
    } else {
        preview.style.display = 'none';
    }
}

function showEmailSuggestions(email) {
    const commonDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'company.com'];
    const [localPart] = email.split('@');
    
    // Remove existing suggestions
    const existingSuggestion = document.querySelector('.email-suggestion');
    if (existingSuggestion) {
        existingSuggestion.remove();
    }
    
    const suggestionDiv = document.createElement('div');
    suggestionDiv.className = 'email-suggestion';
    suggestionDiv.innerHTML = `
        <small style="color: var(--md-sys-color-primary); font-weight: 500;">
            💡 Saran domain: 
            ${commonDomains.map(domain => 
                `<span style="cursor: pointer; margin: 0 4px; padding: 2px 6px; background: rgba(103, 80, 164, 0.1); border-radius: 4px;" 
                 onclick="useEmailSuggestion('${localPart}@${domain}')">${domain}</span>`
            ).join('')}
        </small>
    `;
    suggestionDiv.style.marginTop = '8px';
    
    const emailField = document.getElementById('email').parentNode;
    emailField.appendChild(suggestionDiv);
}

function useEmailSuggestion(email) {
    document.getElementById('email').value = email;
    document.querySelector('.email-suggestion').remove();
    updatePreview();
    showToast('💡 Email telah dilengkapi otomatis', 'info');
}

function validateForm() {
    const form = document.getElementById('supplierForm');
    const formData = new FormData(form);
    let isValid = true;
    
    // Required field validation
    const namaSupplier = formData.get('nama_supplier');
    if (!namaSupplier || namaSupplier.trim() === '') {
        showFieldError(form.querySelector('[name="nama_supplier"]'), 'Nama supplier wajib diisi');
        isValid = false;
    }
    
    // Email validation
    const email = formData.get('email');
    if (email && !isValidEmail(email)) {
        showFieldError(form.querySelector('[name="email"]'), 'Format email tidak valid');
        isValid = false;
    }
    
    // Phone validation
    const telepon = formData.get('telepon');
    if (telepon && !isValidPhone(telepon)) {
        showFieldError(form.querySelector('[name="telepon"]'), 'Format nomor telepon tidak valid');
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
    
    if (field.type === 'email' && value && !isValidEmail(value)) {
        showFieldError(field, 'Format email tidak valid');
        return false;
    }
    
    if (field.type === 'tel' && value && !isValidPhone(value)) {
        showFieldError(field, 'Format nomor telepon tidak valid');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    // Simple validation - just check if it contains numbers
    return phone.length >= 8 && /\d/.test(phone);
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

.nav-links .btn:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), 
                0 4px 12px rgba(0, 0, 0, 0.1), 
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
}

.nav-links .btn-secondary:hover {
    background: linear-gradient(135deg, rgba(98, 91, 113, 0.25) 0%, rgba(98, 91, 113, 0.15) 100%);
}

/* Supplier Preview */
.supplier-preview {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 20px;
    margin: 24px 0;
}

.supplier-preview h4 {
    margin: 0 0 16px 0;
    color: var(--md-sys-color-on-surface);
    font-size: 16px;
    font-weight: 600;
}

.preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.preview-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.preview-label {
    font-size: 12px;
    color: var(--md-sys-color-on-surface-variant);
    font-weight: 500;
}

.preview-value {
    font-weight: 600;
    color: var(--md-sys-color-on-surface);
}

@media (max-width: 768px) {
    .nav-links {
        flex-direction: column;
        align-items: stretch;
    }
    
    .nav-links .btn {
        width: 100%;
        justify-content: center;
    }
    
    .preview-grid {
        grid-template-columns: 1fr;
    }
    
    .preview-item {
        flex-direction: column;
        gap: 4px;
        text-align: center;
    }
}
</style>

<?php
require_once '../../templates/footer.php';
?>
