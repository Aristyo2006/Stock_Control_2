<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

// Set page title
$page_title = "Edit Lokasi Gudang - Stock Control Gudang";

// Include the header template
require_once '../../templates/header.php';
require_once '../../config/database.php';

// Get location ID and fetch data
$id = $_GET['id'] ?? 0;
$query = "SELECT * FROM lokasi_gudang WHERE id_lokasi = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$lokasi = $result->fetch_assoc();

if (!$lokasi) {
    header("Location: index.php?error=Location not found");
    exit;
}

// Handle form submission
if ($_POST) {
    $kode_lokasi = $_POST['kode_lokasi'];
    $nama_lokasi = $_POST['nama_lokasi'];
    $kapasitas = $_POST['kapasitas'] ?: null;
    $deskripsi = $_POST['deskripsi'];
    
    $updateQuery = "UPDATE lokasi_gudang SET 
                    kode_lokasi = ?, 
                    nama_lokasi = ?, 
                    kapasitas = ?, 
                    deskripsi = ? 
                    WHERE id_lokasi = ?";
    
    $updateStmt = $koneksi->prepare($updateQuery);
    $updateStmt->bind_param("ssisi", $kode_lokasi, $nama_lokasi, $kapasitas, $deskripsi, $id);
    
    if ($updateStmt->execute()) {
        $success = "Lokasi berhasil diperbarui!";
        // Refresh location data
        $stmt->execute();
        $result = $stmt->get_result();
        $lokasi = $result->fetch_assoc();
    } else {
        $error = "Gagal memperbarui lokasi: " . $koneksi->error;
    }
}
?>

<div class="container">
    <h2>✏️ Edit Lokasi Gudang</h2>
    
    <div class="nav-links">
        <a href="index.php" class="btn btn-secondary">
            ← Kembali ke Data Lokasi
        </a>
        <a href="tambah.php" class="btn btn-primary">
            + Tambah Lokasi Baru
        </a>
    </div>

    <?php if (isset($success)): ?>
        <div class="message-container message-success">
            <strong>✅ Berhasil!</strong> <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="message-container message-error">
            <strong>❌ Error!</strong> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <!-- Location Info Header -->
        <div class="location-info-header">
            <div class="location-avatar">
                📍
            </div>
            <div class="location-details">
                <h3><?= htmlspecialchars($lokasi['nama_lokasi']) ?></h3>
                <p>Kode: <?= htmlspecialchars($lokasi['kode_lokasi']) ?></p>
                <small>Terakhir diperbarui: <?= date('d M Y H:i', strtotime($lokasi['updated_at'] ?? 'now')) ?></small>
            </div>
            <div class="location-status">
                <?php 
                $kapasitas = $lokasi['kapasitas'];
                $statusColor = $kapasitas ? '#4caf50' : '#ff9800';
                $statusText = $kapasitas ? number_format($kapasitas) . ' unit' : 'Tidak terbatas';
                ?>
                <div class="status-badge" style="background: <?= $statusColor ?>20; color: <?= $statusColor ?>; border: 1px solid <?= $statusColor ?>40;">
                    📦 Kapasitas: <?= $statusText ?>
                </div>
            </div>
        </div>

        <form method="post" id="editLocationForm">
            <div class="form-grid">
                <div class="form-field">
                    <input type="text" name="kode_lokasi" id="kode_lokasi" required placeholder=" " 
                           value="<?= htmlspecialchars($lokasi['kode_lokasi']) ?>">
                    <label for="kode_lokasi">Kode Lokasi</label>
                </div>

                <div class="form-field">
                    <input type="text" name="nama_lokasi" id="nama_lokasi" required placeholder=" " 
                           value="<?= htmlspecialchars($lokasi['nama_lokasi']) ?>">
                    <label for="nama_lokasi">Nama Lokasi</label>
                </div>

                <div class="form-field">
                    <input type="number" name="kapasitas" id="kapasitas" min="0" placeholder=" " 
                           value="<?= $lokasi['kapasitas'] ?>">
                    <label for="kapasitas">Kapasitas (unit)</label>
                </div>
            </div>

            <div class="form-field" style="grid-column: 1 / -1;">
                <textarea name="deskripsi" id="deskripsi" placeholder=" "><?= htmlspecialchars($lokasi['deskripsi']) ?></textarea>
                <label for="deskripsi">Deskripsi Lokasi</label>
            </div>

            <!-- Location Info Section -->
            <div class="location-info">
                <h4>📊 Informasi Lokasi</h4>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Kode Saat Ini</span>
                        <span class="info-value"><?= htmlspecialchars($lokasi['kode_lokasi']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nama Saat Ini</span>
                        <span class="info-value"><?= htmlspecialchars($lokasi['nama_lokasi']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kapasitas Saat Ini</span>
                        <span class="info-value">
                            <?= $lokasi['kapasitas'] ? number_format($lokasi['kapasitas']) . ' unit' : 'Tidak terbatas' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">
                    ❌ Batal
                </button>
                <button type="button" class="btn btn-edit" onclick="resetForm()">
                    🔄 Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    💾 Perbarui Lokasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<!-- Change Confirmation Dialog -->
<div class="confirmation-dialog" id="changeConfirmDialog">
    <div class="confirmation-content">
        <div class="confirmation-icon">⚠️</div>
        <h3 class="confirmation-title">Konfirmasi Perubahan</h3>
        <div class="confirmation-message">
            <p>Anda telah mengubah data lokasi. Apakah Anda yakin ingin menyimpan perubahan?</p>
            <div id="changesSummary" class="changes-summary"></div>
        </div>
        <div class="confirmation-actions">
            <button type="button" class="btn btn-secondary" onclick="closeChangeConfirm()">
                ❌ Batal
            </button>
            <button type="button" class="btn btn-primary" onclick="confirmChanges()">
                💾 Ya, Simpan
            </button>
        </div>
    </div>
</div>

<style>
/* Location Info Header */
.location-info-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 24px;
    background: linear-gradient(135deg, rgba(103, 80, 164, 0.1) 0%, rgba(103, 80, 164, 0.05) 100%);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(103, 80, 164, 0.2);
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: 0 4px 16px rgba(103, 80, 164, 0.1);
}

[data-theme="dark"] .location-info-header {
    background: linear-gradient(135deg, rgba(208, 188, 255, 0.1) 0%, rgba(208, 188, 255, 0.05) 100%);
    border: 1px solid rgba(208, 188, 255, 0.2);
}

.location-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--md-sys-color-primary) 0%, rgba(103, 80, 164, 0.8) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 4px 12px rgba(103, 80, 164, 0.3);
}

.location-details {
    flex: 1;
}

.location-details h3 {
    margin: 0 0 4px 0;
    color: var(--md-sys-color-on-surface);
    font-size: 20px;
    font-weight: 600;
}

.location-details p {
    margin: 0 0 4px 0;
    color: var(--md-sys-color-on-surface-variant);
    font-weight: 500;
}

.location-details small {
    color: var(--md-sys-color-outline);
    font-size: 12px;
}

.location-status {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Location Info */
.location-info {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 20px;
    margin: 24px 0;
}

.location-info h4 {
    margin: 0 0 16px 0;
    color: var(--md-sys-color-on-surface);
    font-size: 16px;
    font-weight: 600;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.info-label {
    font-size: 12px;
    color: var(--md-sys-color-on-surface-variant);
    font-weight: 500;
}

.info-value {
    font-weight: 600;
    color: var(--md-sys-color-on-surface);
}

/* Changes Summary */
.changes-summary {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
    max-height: 200px;
    overflow-y: auto;
}

.change-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.change-item:last-child {
    border-bottom: none;
}

.change-field {
    font-weight: 500;
    color: var(--md-sys-color-on-surface);
}

.change-values {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
}

.old-value {
    color: var(--md-sys-color-error);
    text-decoration: line-through;
}

.new-value {
    color: var(--md-sys-color-primary);
    font-weight: 600;
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

/* Responsive Design */
@media (max-width: 768px) {
    .location-info-header {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .location-status {
        align-items: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .info-item {
        flex-direction: column;
        gap: 4px;
        text-align: center;
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
</style>

<script>
// Store original values for comparison
const originalValues = {
    kode_lokasi: "<?= htmlspecialchars($lokasi['kode_lokasi']) ?>",
    nama_lokasi: "<?= htmlspecialchars($lokasi['nama_lokasi']) ?>",
    kapasitas: "<?= $lokasi['kapasitas'] ?>",
    deskripsi: "<?= htmlspecialchars($lokasi['deskripsi']) ?>"
};

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('editLocationForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Add floating particles to form container
    const formContainer = document.querySelector('.form-container');
    if (formContainer) {
        createFloatingParticles(formContainer);
    }
    
    // Enhanced form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check if there are changes
        const changes = getChanges();
        if (Object.keys(changes).length === 0) {
            showToast('ℹ️ Tidak ada perubahan untuk disimpan', 'info');
            return;
        }
        
        // Show change confirmation
        showChangeConfirmation(changes);
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
        input.addEventListener('input', updateChangeIndicator);
    });
});

function getChanges() {
    const form = document.getElementById('editLocationForm');
    const formData = new FormData(form);
    const changes = {};
    
    for (const [key, originalValue] of Object.entries(originalValues)) {
        const currentValue = formData.get(key) || '';
        if (currentValue !== originalValue) {
            changes[key] = {
                old: originalValue,
                new: currentValue
            };
        }
    }
    
    return changes;
}

function updateChangeIndicator() {
    const changes = getChanges();
    const hasChanges = Object.keys(changes).length > 0;
    
    // Update form container appearance
    const formContainer = document.querySelector('.form-container');
    if (hasChanges) {
        formContainer.style.borderColor = 'var(--md-sys-color-primary)';
        formContainer.style.boxShadow = '0 0 0 2px rgba(103, 80, 164, 0.2), 0 8px 32px rgba(0, 0, 0, 0.1)';
    } else {
        formContainer.style.borderColor = '';
        formContainer.style.boxShadow = '';
    }
}

function showChangeConfirmation(changes) {
    const dialog = document.getElementById('changeConfirmDialog');
    const summary = document.getElementById('changesSummary');
    
    // Build changes summary
    let summaryHTML = '<h5>Perubahan yang akan disimpan:</h5>';
    for (const [field, change] of Object.entries(changes)) {
        const fieldName = getFieldDisplayName(field);
        summaryHTML += `
            <div class="change-item">
                <span class="change-field">${fieldName}</span>
                <div class="change-values">
                    <span class="old-value">${change.old || '(kosong)'}</span>
                    <span>→</span>
                    <span class="new-value">${change.new || '(kosong)'}</span>
                </div>
            </div>
        `;
    }
    
    summary.innerHTML = summaryHTML;
    dialog.classList.add('show');
    
    // Add escape key listener
    document.addEventListener('keydown', handleEscapeKey);
}

function getFieldDisplayName(field) {
    const fieldNames = {
        kode_lokasi: 'Kode Lokasi',
        nama_lokasi: 'Nama Lokasi',
        kapasitas: 'Kapasitas',
        deskripsi: 'Deskripsi'
    };
    return fieldNames[field] || field;
}

function closeChangeConfirm() {
    const dialog = document.getElementById('changeConfirmDialog');
    dialog.classList.remove('show');
    
    // Remove escape key listener
    document.removeEventListener('keydown', handleEscapeKey);
}

function handleEscapeKey(e) {
    if (e.key === 'Escape') {
        closeChangeConfirm();
    }
}

function confirmChanges() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    const dialog = document.getElementById('changeConfirmDialog');
    
    // Close dialog and show loading
    dialog.classList.remove('show');
    loadingOverlay.classList.add('show');
    
    // Show update progress
    showToast('💾 Memperbarui lokasi...', 'info');
    
    // Submit form after short delay
    setTimeout(() => {
        document.getElementById('editLocationForm').submit();
    }, 1500);
}

function resetForm() {
    const form = document.getElementById('editLocationForm');
    
    // Reset to original values
    for (const [key, value] of Object.entries(originalValues)) {
        const field = form.querySelector(`[name="${key}"]`);
        if (field) {
            field.value = value;
            clearFieldError(field);
        }
    }
    
    // Clear change indicators
    updateChangeIndicator();
    
    showToast('🔄 Form telah direset ke nilai asli', 'info');
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

// Close dialog when clicking outside
document.getElementById('changeConfirmDialog').addEventListener('click', function(e) {
    if (e.target === this) {
        closeChangeConfirm();
    }
});
</script>

<?php
require_once '../../templates/footer.php';
?>
