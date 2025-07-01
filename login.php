<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: pages/dashboard.php");
    exit;
}
require_once 'templates/header.php';
// Menentukan judul halaman
$page_title = "Login - Stock Control Gudang";
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Import Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
    /* Material 3 Design System with Dark/Light Mode Support */
    :root {
      /* Primary Colors - Light */
      --md-sys-color-primary: #6750a4;
      --md-sys-color-on-primary: #ffffff;
      --md-sys-color-primary-container: #eaddff;
      --md-sys-color-on-primary-container: #21005d;
      --md-sys-color-surface: #fef7ff;
      --md-sys-color-on-surface: #1c1b1f;
      --md-sys-color-surface-container: #f3edf7;
      --md-sys-color-outline: #79747e;
      --md-sys-color-background: #fef7ff;
      --md-sys-color-on-background: #1c1b1f;
      --blob-color-1: rgba(103, 80, 164, 0.08);
      --blob-color-2: rgba(103, 80, 164, 0.12);
      --blob-color-3: rgba(103, 80, 164, 0.06);
      --blob-color-4: rgba(103, 80, 164, 0.15);
    }

    /* Dark Theme */
    [data-theme="dark"] {
      /* Primary Colors - Dark */
      --md-sys-color-primary: #d0bcff;
      --md-sys-color-on-primary: #381e72;
      --md-sys-color-primary-container: #4f378b;
      --md-sys-color-on-primary-container: #eaddff;
      --md-sys-color-surface: #141218;
      --md-sys-color-on-surface: #e6e0e9;
      --md-sys-color-surface-container: #211f26;
      --md-sys-color-outline: #938f99;
      --md-sys-color-background: #141218;
      --md-sys-color-on-background: #e6e0e9;
      --blob-color-1: rgba(208, 188, 255, 0.08);
      --blob-color-2: rgba(208, 188, 255, 0.12);
      --blob-color-3: rgba(208, 188, 255, 0.06);
      --blob-color-4: rgba(208, 188, 255, 0.15);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Roboto", sans-serif;
      font-size: 16px;
      font-weight: 400;
      line-height: 1.5;
      color: var(--md-sys-color-on-surface);
      background-color: var(--md-sys-color-background);
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Main Gradient Blob Background */
    body::before {
      content: "";
      position: fixed;
      top: -20%;
      left: -20%;
      width: 140%;
      height: 140%;
      z-index: -2;
      background: radial-gradient(circle at 20% 80%, var(--blob-color-1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, var(--blob-color-2) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, var(--blob-color-3) 0%, transparent 50%);
      animation: blob-morph 20s ease-in-out infinite;
    }

    /* Secondary Blob Layer */
    body::after {
      content: "";
      position: fixed;
      top: -30%;
      right: -30%;
      width: 160%;
      height: 160%;
      z-index: -1;
      background: radial-gradient(ellipse at 70% 70%, var(--blob-color-4) 0%, transparent 60%),
        radial-gradient(ellipse at 30% 30%, var(--blob-color-2) 0%, transparent 40%);
      animation: blob-float 25s ease-in-out infinite reverse;
    }

    @keyframes blob-morph {
      0%, 100% {
        transform: translate(0, 0) rotate(0deg) scale(1);
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
      }
      25% {
        transform: translate(30px, -50px) rotate(90deg) scale(1.1);
        border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
      }
      50% {
        transform: translate(-20px, 20px) rotate(180deg) scale(0.9);
        border-radius: 50% 60% 30% 60% / 30% 60% 70% 40%;
      }
      75% {
        transform: translate(50px, 10px) rotate(270deg) scale(1.05);
        border-radius: 60% 40% 60% 30% / 70% 30% 60% 40%;
      }
    }

    @keyframes blob-float {
      0%, 100% {
        transform: translate(0, 0) rotate(0deg);
      }
      33% {
        transform: translate(-30px, -30px) rotate(120deg);
      }
      66% {
        transform: translate(30px, 20px) rotate(240deg);
      }
    }

    /* Enhanced Login Wrapper with iOS 16 Liquid Glass Effect */
    .login-wrapper {
      max-width: 420px;
      margin: 10vh auto;
      padding: 40px;
      position: relative;
      z-index: 1;
      border-radius: 24px;

      /* iOS 16 Liquid Glass Effect */
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);

      /* Enhanced borders and shadows */
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 2px 8px rgba(0, 0, 0, 0.05), inset 0 1px 0 rgba(255, 255, 255, 0.3),
        inset 0 -1px 0 rgba(255, 255, 255, 0.1);

      /* Subtle animation */
      animation: glass-float 6s ease-in-out infinite;
      transition: all 0.3s ease;
      overflow: hidden;
    }

    /* Dark theme adjustments for liquid glass */
    [data-theme="dark"] .login-wrapper {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
      border: 1px solid rgba(255, 255, 255, 0.15);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 0 2px 8px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.2), inset
        0 -1px 0 rgba(255, 255, 255, 0.05);
    }

    /* Floating animation for glass effect */
    @keyframes glass-float {
      0%, 100% {
        transform: translateY(0px) rotate(0deg);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 0 2px 8px rgba(0, 0, 0, 0.05), inset 0 1px 0 rgba(255, 255, 255, 0.3),
          inset 0 -1px 0 rgba(255, 255, 255, 0.1);
      }
      50% {
        transform: translateY(-5px) rotate(0.5deg);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.4),
          inset 0 -1px 0 rgba(255, 255, 255, 0.15);
      }
    }

    /* Enhanced login title */
    .login-wrapper h2 {
      text-align: center;
      margin-bottom: 32px;
      color: var(--md-sys-color-primary);
      font-weight: 600;
      font-size: 28px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    /* Form Styles */
    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    label {
      font-weight: 500;
      color: var(--md-sys-color-on-surface);
      margin-bottom: 8px;
      display: block;
    }

    /* Enhanced form inputs with glass effect */
    input[type="text"],
    input[type="password"],
    input[type="email"] {
      width: 100%;
      padding: 16px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      color: var(--md-sys-color-on-surface);
      font-family: "Roboto", sans-serif;
      font-size: 16px;
      transition: all 0.3s ease;
      outline: none;
    }

    [data-theme="dark"] input[type="text"],
    [data-theme="dark"] input[type="password"],
    [data-theme="dark"] input[type="email"] {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="email"]:focus {
      background: rgba(255, 255, 255, 0.15);
      border-color: var(--md-sys-color-primary);
      box-shadow: 0 0 0 2px rgba(103, 80, 164, 0.2), 0 4px 12px rgba(0, 0, 0, 0.1), 0 0 20px rgba(103, 80, 164, 0.1);
      transform: translateY(-1px);
    }

    /* Enhanced login button with glass effect */
    button[type="submit"],
    input[type="submit"] {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      padding: 16px 24px;
      margin-top: 16px;
      background: linear-gradient(135deg, var(--md-sys-color-primary) 0%, rgba(103, 80, 164, 0.9) 100%);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 50px;
      color: var(--md-sys-color-on-primary);
      font-family: "Roboto", sans-serif;
      font-size: 16px;
      font-weight: 600;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 16px rgba(103, 80, 164, 0.3), 0 2px 8px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }

    button[type="submit"]:hover,
    input[type="submit"]:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(103, 80, 164, 0.4), 0 4px 12px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.4);
      background: linear-gradient(135deg, rgba(103, 80, 164, 1) 0%, rgba(103, 80, 164, 0.95) 100%);
    }

    /* Error Messages */
    .form-error {
      color: #ff4d4f;
      background-color: rgba(255, 77, 79, 0.1);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 77, 79, 0.3);
      padding: 12px 16px;
      border-radius: 12px;
      margin-bottom: 16px;
      font-weight: 500;
      box-shadow: 0 4px 12px rgba(255, 77, 79, 0.1);
    }

    /* Theme Toggle Button */
    .theme-toggle {
      position: fixed;
      top: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      border: none;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      color: var(--md-sys-color-on-surface);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
      z-index: 1000;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .theme-toggle:hover {
      background-color: rgba(255, 255, 255, 0.15);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
      transform: scale(1.05);
    }

    /* Add subtle light refraction effects */
    .login-wrapper::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
      border-radius: 24px 24px 0 0;
    }

    .login-wrapper::after {
      content: "";
      position: absolute;
      top: 1px;
      left: 1px;
      right: 1px;
      height: 50%;
      background: linear-gradient(180deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
      border-radius: 23px 23px 0 0;
      pointer-events: none;
    }

    /* Responsive adjustments for mobile */
    @media (max-width: 768px) {
      .login-wrapper {
        margin: 5vh auto;
        padding: 32px 24px;
        max-width: 90%;
      }

      .login-wrapper h2 {
        font-size: 24px;
      }
    }
    </style>
</head>
<body>
    <!-- Theme Toggle Button -->
    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle dark mode">
        <span class="theme-toggle-icon">☀️</span>
    </button>

    <div class="login-wrapper">
        <h2>Login Sistem</h2>

        <?php if (isset($_GET['error'])): ?>
            <p class="form-error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <form action="proses/login_proses.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>

    <script>
    // Theme toggle functionality
    document.addEventListener("DOMContentLoaded", () => {
        // Initialize theme
        initializeTheme();
        
        // Add floating particles
        createFloatingParticles();
        
        // Add light refraction effect
        addLightRefractionEffect();
        
        // Enhance form inputs
        enhanceFormInputs();
    });

    function initializeTheme() {
        // Check for saved theme preference or default to dark mode
        const savedTheme = localStorage.getItem("theme") || "dark";
        
        // Apply theme
        document.documentElement.setAttribute("data-theme", savedTheme);
        
        // Update toggle button
        updateThemeToggle(savedTheme);
        
        // Add theme toggle event listener
        const themeToggle = document.getElementById("theme-toggle");
        if (themeToggle) {
            themeToggle.addEventListener("click", toggleTheme);
        }
    }

    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute("data-theme");
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        
        // Apply new theme
        document.documentElement.setAttribute("data-theme", newTheme);
        
        // Save preference
        localStorage.setItem("theme", newTheme);
        
        // Update toggle button
        updateThemeToggle(newTheme);
        
        // Add a subtle animation effect
        document.body.style.transform = "scale(0.98)";
        setTimeout(() => {
            document.body.style.transform = "scale(1)";
        }, 150);
    }

    function updateThemeToggle(theme) {
        const themeToggle = document.getElementById("theme-toggle");
        const themeIcon = document.querySelector(".theme-toggle-icon");
        
        if (themeToggle && themeIcon) {
            if (theme === "dark") {
                themeIcon.textContent = "☀️";
                themeToggle.setAttribute("aria-label", "Switch to light mode");
            } else {
                themeIcon.textContent = "🌙";
                themeToggle.setAttribute("aria-label", "Switch to dark mode");
            }
        }
    }

    function createFloatingParticles() {
        const loginWrapper = document.querySelector(".login-wrapper");
        if (!loginWrapper) return;
        
        // Create subtle floating particles for premium effect
        for (let i = 0; i < 6; i++) {
            const particle = document.createElement("div");
            particle.className = "particle";
            particle.style.position = "absolute";
            particle.style.width = "2px";
            particle.style.height = "2px";
            particle.style.background = "rgba(255, 255, 255, 0.6)";
            particle.style.borderRadius = "50%";
            particle.style.left = Math.random() * 100 + "%";
            particle.style.pointerEvents = "none";
            
            // Add animation
            particle.style.animation = `particle-float ${8 + Math.random() * 4}s linear infinite`;
            particle.style.animationDelay = Math.random() * 8 + "s";
            
            loginWrapper.appendChild(particle);
        }
        
        // Add particle animation keyframes
        const style = document.createElement("style");
        style.textContent = `
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
        `;
        document.head.appendChild(style);
    }

    function addLightRefractionEffect() {
        const loginWrapper = document.querySelector(".login-wrapper");
        if (!loginWrapper) return;
        
        // Add mouse move effect for light refraction
        loginWrapper.addEventListener("mousemove", (e) => {
            const rect = loginWrapper.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            
            // Create subtle light following mouse
            loginWrapper.style.background = `
                linear-gradient(
                    ${135 + (x - 50) * 0.2}deg,
                    rgba(255, 255, 255, ${0.1 + (100 - Math.abs(x - 50)) * 0.001}) 0%,
                    rgba(255, 255, 255, ${0.05 + (100 - Math.abs(y - 50)) * 0.0005}) 100%
                )
            `;
        });
        
        loginWrapper.addEventListener("mouseleave", () => {
            // Reset to default background
            const isDark = document.documentElement.getAttribute("data-theme") === "dark";
            loginWrapper.style.background = isDark
                ? "linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%)"
                : "linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%)";
        });
    }

    function enhanceFormInputs() {
        const inputs = document.querySelectorAll("input");
        
        inputs.forEach((input) => {
            // Add ripple effect on focus
            input.addEventListener("focus", function() {
                this.style.transform = "translateY(-1px) scale(1.02)";
            });
            
            input.addEventListener("blur", function() {
                this.style.transform = "translateY(0px) scale(1)";
            });
            
            // Add typing effect
            input.addEventListener("input", function() {
                this.style.boxShadow = `
                    0 0 0 2px rgba(103, 80, 164, 0.3),
                    0 4px 12px rgba(0, 0, 0, 0.1),
                    0 0 20px rgba(103, 80, 164, 0.2)
                `;
                
                setTimeout(() => {
                    this.style.boxShadow = "";
                }, 200);
            });
        });
    }
    </script>
</body>
</html>

<?php
require_once 'templates/footer.php';
?>
