<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}
require_once '../../templates/header.php';
require_once '../../config/database.php';

$query = "SELECT * FROM pelanggan ORDER BY id_pelanggan ASC";
$result = $koneksi->query($query);
?>
<style>
/* Enhanced Liquid Glass Table Styling */
.glass-container {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 24px;
    margin: 20px 0;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 
                0 2px 8px rgba(0, 0, 0, 0.05), 
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(255, 255, 255, 0.1);
    position: relative;
    overflow: hidden;
}

[data-theme="dark"] .glass-container {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 
                0 2px 8px rgba(0, 0, 0, 0.2), 
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                inset 0 -1px 0 rgba(255, 255, 255, 0.05);
}

/* Glass table styling */
.glass-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1), 
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .glass-table {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.glass-table thead {
    background: linear-gradient(135deg, rgba(103, 80, 164, 0.2) 0%, rgba(103, 80, 164, 0.1) 100%);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
}

[data-theme="dark"] .glass-table thead {
    background: linear-gradient(135deg, rgba(208, 188, 255, 0.15) 0%, rgba(208, 188, 255, 0.08) 100%);
}

.glass-table th {
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    color: var(--md-sys-color-on-surface);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.glass-table th:first-child {
    border-top-left-radius: 16px;
}

.glass-table th:last-child {
    border-top-right-radius: 16px;
}

.glass-table td {
    padding: 16px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    color: var(--md-sys-color-on-surface-variant);
    transition: all 0.3s ease;
    position: relative;
}

.glass-table tbody tr {
    transition: all 0.3s ease;
    position: relative;
}

.glass-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

[data-theme="dark"] .glass-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.05);
}

.glass-table tbody tr:last-child td:first-child {
    border-bottom-left-radius: 16px;
}

.glass-table tbody tr:last-child td:last-child {
    border-bottom-right-radius: 16px;
}

.glass-table tbody tr:last-child td {
    border-bottom: none;
}

/* Enhanced action buttons with glass effect */
.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    margin: 0 4px;
    border: none;
    border-radius: 20px;
    font-family: "Roboto", sans-serif;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.action-btn-edit {
    background: linear-gradient(135deg, rgba(125, 82, 96, 0.2) 0%, rgba(125, 82, 96, 0.1) 100%);
    color: var(--md-sys-color-on-tertiary-container);
    box-shadow: 0 2px 8px rgba(125, 82, 96, 0.2);
}

.action-btn-delete {
    background: linear-gradient(135deg, rgba(186, 26, 26, 0.2) 0%, rgba(186, 26, 26, 0.1) 100%);
    color: #ff6b6b;
    box-shadow: 0 2px 8px rgba(186, 26, 26, 0.2);
}

.action-btn:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.action-btn-edit:hover {
    background: linear-gradient(135deg, rgba(125, 82, 96, 0.3) 0%, rgba(125, 82, 96, 0.2) 100%);
    box-shadow: 0 4px 12px rgba(125, 82, 96, 0.3);
}

.action-btn-delete:hover {
    background: linear-gradient(135deg, rgba(186, 26, 26, 0.3) 0%, rgba(186, 26, 26, 0.2) 100%);
    box-shadow: 0 4px 12px rgba(186, 26, 26, 0.3);
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

/* Responsive design */
@media (max-width: 768px) {
    .glass-container {
        padding: 16px;
        margin: 16px 0;
        border-radius: 16px;
    }
    
    .glass-table {
        font-size: 14px;
    }
    
    .glass-table th,
    .glass-table td {
        padding: 12px 8px;
    }
    
    .action-btn {
        padding: 6px 12px;
        font-size: 11px;
        margin: 2px;
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

/* Add floating particles effect */
.glass-container .particle {
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
    <h2>👥 Data Pelanggan</h2>
    
    <div class="nav-links">
        <a href="../../pages/dashboard.php" class="btn btn-secondary">
            ← Kembali ke Dashboard
        </a>
        <a href="tambah.php" class="btn btn-primary">
            + Tambah Pelanggan
        </a>
    </div>

 <div class="glass-container">
        <table class="glass-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pelanggan</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): $no = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                    <td><?= htmlspecialchars($row['telepon']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id_pelanggan']; ?>">Edit</a> |
                        <a href="hapus.php?id=<?= $row['id_pelanggan']; ?>" onclick="return confirm('Yakin hapus pelanggan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
            <td colspan="6" style="text-align: center; padding: 40px; color: var(--md-sys-color-on-surface-variant);">
                            👥 Tidak ada data Pelanggan.
        </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>


<script>
document.addEventListener("DOMContentLoaded", () => {
    // Add floating particles to glass container
    const glassContainer = document.querySelector('.glass-container');
    if (glassContainer) {
        createFloatingParticles(glassContainer);
    }
    
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.glass-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01) translateZ(10px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) translateZ(0px)';
        });
    });
    
    // Add ripple effect to action buttons
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.6)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.pointerEvents = 'none';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.parentNode.removeChild(ripple);
                }
            }, 600);
        });
    });
});

function createFloatingParticles(container) {
    // Create subtle floating particles for premium effect
    for (let i = 0; i < 4; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = 8 + Math.random() * 4 + 's';
        container.appendChild(particle);
    }
}

// Add ripple animation keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

<?php
// Include the footer template
require_once '../../templates/footer.php';
?>
