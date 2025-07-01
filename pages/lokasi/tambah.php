<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

// Set page title
$page_title = "Tambah Lokasi Gudang - Stock Control Gudang";

// Include the header template
require_once '../../templates/header.php';
?>

<div class="container">
    <h2>📍 Tambah Lokasi Gudang Baru</h2>
    
    <div class="nav-links">
        <a href="index.php" class="btn btn-secondary">
            ← Kembali ke Data Lokasi
        </a>
    </div>

    <div class="form-container">
        <form action="../../proses/simpan_lokasi.php" method="post" id="locationForm">
            <div class="form-grid">
                <div class="form-field">
                    <input type="text" name="kode_lokasi" id="kode_lokasi" required placeholder=" ">
                    <label for="kode_lokasi">Kode Lokasi</label>
                </div>

                <div class="form-field">
                    <input type="text" name="nama_lokasi" id="nama_lokasi" required placeholder=" ">
                    <label for="nama_lokasi">Nama Lokasi</label>
                </div>

                <div class="form-field">
                    <input type="number" name="kapasitas" id="kapasitas" min="0" placeholder=" ">
                    <label for="kapasitas">Kapasitas (unit)</label>
                </div>
            </div>

            <div class="form-field" style="grid-column: 1 / -1;">
                <textarea name="deskripsi" id="deskripsi" placeholder=" "></textarea>
                <label for="deskripsi">Deskripsi Lokasi</label>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">
                    ❌ Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    💾 Simpan Lokasi
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
    const form = document.getElementById('locationForm');
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
                showToast('✅ Lokasi berhasil ditambahkan!', 'success');
                this.submit();
            }, 1000);
        } else {
            loadingOverlay.classList.remove('show');
        }
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });
    
    // Auto-generate location code suggestion
    const namaLokasi = document.getElementById('nama_lokasi');
    const kodeLokasi = document.getElementById('kode_lokasi');
    
    namaLokasi.addEventListener('input', function() {
        if (!kodeLokasi.value) {
            const suggestion = generateLocationCode(this.value);
            if (suggestion) {
                showCodeSuggestion(suggestion);
            }
        }
    });
});

function generateLocationCode(namaLokasi) {
    if (!namaLokasi) return '';
    
    // Generate code from first letters of each word
    const words = namaLokasi.trim().split(' ');
    let code = '';
    
    words.forEach(word => {
        if (word.length > 0) {
            code += word.charAt(0).toUpperCase();
        }
    });
    
    // Add random number if code is too short
    if (code.length < 3) {
        code += Math.floor(Math.random() * 100).toString().padStart(2, '0');
    }
    
    return code;
}

function showCodeSuggestion(suggestion) {
    // Remove existing suggestion
    const existingSuggestion = document.querySelector('.code-suggestion');
    if (existingSuggestion) {
        existingSuggestion.remove();
    }
    
    const suggestionDiv = document.createElement('div');
    suggestionDiv.className = 'code-suggestion';
    suggestionDiv.innerHTML = `
        <small style="color: var(--md-sys-color-primary); font-weight: 500; cursor: pointer;" onclick="useCodeSuggestion('${suggestion}')">
            💡 Saran kode: ${suggestion} (klik untuk gunakan)
        </small>
    `;
    suggestionDiv.style.marginTop = '8px';
    
    const kodeLokasi = document.getElementById('kode_lokasi').parentNode;
    kodeLokasi.appendChild(suggestionDiv);
}

function useCodeSuggestion(code) {
    document.getElementById('kode_lokasi').value = code;
    document.querySelector('.code-suggestion').remove();
    showToast('💡 Kode lokasi telah diisi otomatis', 'info');
}

function validateForm() {
    const form = document.getElementById('locationForm');
    const formData = new FormData(form);
    let isValid = true;
    
    // Required field validation
    const requiredFields = ['kode_lokasi', 'nama_lokasi'];
    
    requiredFields.forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        const value = formData.get(fieldName);
        
        if (!value || value.trim() === '') {
            showFieldError(field, 'Field ini wajib diisi');
            isValid = false;
        }
    });
    
    // Capacity validation
    const kapasitas = formData.get('kapasitas');
    if (kapasitas && parseFloat(kapasitas) < 0) {
        showFieldError(form.querySelector('[name="kapasitas"]'), 'Kapasitas tidak boleh negatif');
        isValid = false;
    }
    
    // Code format validation
    const kode = formData.get('kode_lokasi');
    if (kode && kode.length < 2) {
        showFieldError(form.querySelector('[name="kode_lokasi"]'), 'Kode lokasi minimal 2 karakter');
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
    
    if (field.type === 'number' && value && parseFloat(value) < 0) {
        showFieldError(field, 'Nilai tidak boleh negatif');
        return false;
    }
    
    if (field.name === 'kode_lokasi' && value && value.length < 2) {
        showFieldError(field, 'Kode lokasi minimal 2 karakter');
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

@media (max-width: 768px) {
    .nav-links {
        flex-direction: column;
        align-items: stretch;
    }
    
    .nav-links .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php
require_once '../../templates/footer.php';
?>
