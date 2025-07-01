// Material 3 Interactive Enhancements with Dark/Light Mode
document.addEventListener("DOMContentLoaded", () => {
  console.log("Material 3 Design System Loading...")

  // Initialize theme first
  initializeTheme()

  // Create blob elements
  createBlobElements()

  // Initialize all other components
  initializeComponents()
})

function initializeTheme() {
  // Check for saved theme preference or default to light mode
  const savedTheme = localStorage.getItem("theme") || "light"
  const systemPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches

  // Use saved theme, or system preference if no saved theme
  const initialTheme = savedTheme !== "system" ? savedTheme : systemPrefersDark ? "dark" : "light"

  // Apply theme
  document.documentElement.setAttribute("data-theme", initialTheme)

  // Update toggle button
  updateThemeToggle(initialTheme)

  // Add theme toggle event listener
  const themeToggle = document.getElementById("theme-toggle")
  if (themeToggle) {
    themeToggle.addEventListener("click", toggleTheme)
  }

  // Listen for system theme changes
  window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (e) => {
    if (localStorage.getItem("theme") === "system") {
      const newTheme = e.matches ? "dark" : "light"
      document.documentElement.setAttribute("data-theme", newTheme)
      updateThemeToggle(newTheme)
    }
  })
}

function toggleTheme() {
  const currentTheme = document.documentElement.getAttribute("data-theme")
  const newTheme = currentTheme === "dark" ? "light" : "dark"

  // Apply new theme
  document.documentElement.setAttribute("data-theme", newTheme)

  // Save preference
  localStorage.setItem("theme", newTheme)

  // Update toggle button
  updateThemeToggle(newTheme)

  // Show toast notification
  const messages = {
    dark: "🌙 Mode Gelap Aktif!",
    light: "☀️ Mode Terang Aktif!",
  }
  showToast(messages[newTheme], "success")

  // Add a subtle animation effect
  document.body.style.transform = "scale(0.98)"
  setTimeout(() => {
    document.body.style.transform = "scale(1)"
  }, 150)
}

function updateThemeToggle(theme) {
  const themeToggle = document.getElementById("theme-toggle")
  const themeIcon = document.querySelector(".theme-toggle-icon")

  if (themeToggle && themeIcon) {
    if (theme === "dark") {
      themeIcon.textContent = "☀️"
      themeToggle.setAttribute("aria-label", "Switch to light mode")
    } else {
      themeIcon.textContent = "🌙"
      themeToggle.setAttribute("aria-label", "Switch to dark mode")
    }
  }
}

function createBlobElements() {
  // Create blob container
  const blobContainer = document.createElement("div")
  blobContainer.className = "blob-container"

  // Create individual blob elements
  for (let i = 0; i < 3; i++) {
    const blob = document.createElement("div")
    blob.className = "blob"
    blobContainer.appendChild(blob)
  }

  // Add to body
  document.body.appendChild(blobContainer)
}

function enhanceLoginForm() {
  const loginWrapper = document.querySelector(".login-wrapper")

  if (loginWrapper) {
    // Add body class for login page specific styling
    document.body.classList.add("login-page")

    // Create floating particles effect
    createFloatingParticles(loginWrapper)

    // Add dynamic light refraction effect
    addLightRefractionEffect(loginWrapper)

    // Enhanced input interactions
    enhanceLoginInputs(loginWrapper)
  }
}

function createFloatingParticles(container) {
  // Create subtle floating particles for premium effect
  for (let i = 0; i < 6; i++) {
    const particle = document.createElement("div")
    particle.className = "particle"
    particle.style.left = Math.random() * 100 + "%"
    particle.style.animationDelay = Math.random() * 8 + "s"
    particle.style.animationDuration = 8 + Math.random() * 4 + "s"
    container.appendChild(particle)
  }
}

function addLightRefractionEffect(container) {
  // Add mouse move effect for light refraction
  container.addEventListener("mousemove", (e) => {
    const rect = container.getBoundingClientRect()
    const x = ((e.clientX - rect.left) / rect.width) * 100
    const y = ((e.clientY - rect.top) / rect.height) * 100

    // Create subtle light following mouse
    container.style.background = `
      linear-gradient(
        ${135 + (x - 50) * 0.2}deg,
        rgba(255, 255, 255, ${0.1 + (100 - Math.abs(x - 50)) * 0.001}) 0%,
        rgba(255, 255, 255, ${0.05 + (100 - Math.abs(y - 50)) * 0.0005}) 100%
      )
    `
  })

  container.addEventListener("mouseleave", () => {
    // Reset to default background
    const isDark = document.documentElement.getAttribute("data-theme") === "dark"
    container.style.background = isDark
      ? "linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%)"
      : "linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%)"
  })
}

function enhanceLoginInputs(container) {
  const inputs = container.querySelectorAll("input")

  inputs.forEach((input) => {
    // Add ripple effect on focus
    input.addEventListener("focus", function () {
      this.style.transform = "translateY(-1px) scale(1.02)"
    })

    input.addEventListener("blur", function () {
      this.style.transform = "translateY(0px) scale(1)"
    })

    // Add typing effect
    input.addEventListener("input", function () {
      this.style.boxShadow = `
        0 0 0 2px rgba(103, 80, 164, 0.3),
        0 4px 12px rgba(0, 0, 0, 0.1),
        0 0 20px rgba(103, 80, 164, 0.2)
      `

      setTimeout(() => {
        this.style.boxShadow = ""
      }, 200)
    })
  })
}

function initializeComponents() {
  try {
    // Convert links to buttons
    convertLinksToButtons()

    // Add ripple effects
    addRippleEffects()

    // Enhance form inputs
    enhanceFormInputs()

    // Add loading states to forms
    addLoadingStates()

    // Enhance tables
    enhanceDataTables()

    // Add keyboard navigation
    addKeyboardNavigation()

    // Add theme-aware focus management
    addThemeAwareFocus()

    // Enhance login form if present
    enhanceLoginForm()

    console.log("Material 3 Design System Loaded Successfully!")
  } catch (error) {
    console.error("Error initializing Material 3 components:", error)
  }
}

function addThemeAwareFocus() {
  // Enhanced focus indicators that work with both themes
  const focusableElements = document.querySelectorAll(
    'button, .btn, input, select, textarea, a[href], [tabindex]:not([tabindex="-1"])',
  )

  focusableElements.forEach((element) => {
    element.addEventListener("focus", function () {
      this.style.outline = "2px solid var(--md-sys-color-primary)"
      this.style.outlineOffset = "2px"
    })

    element.addEventListener("blur", function () {
      this.style.outline = ""
      this.style.outlineOffset = ""
    })
  })
}

function convertLinksToButtons() {
  // Add icons to dashboard links
  const dashboardLinks = document.querySelectorAll(".dashboard-list a")
  dashboardLinks.forEach((link) => {
    const linkText = link.textContent.trim()
    let icon = ""

    if (linkText.includes("Produk")) {
      icon = "📦 "
    } else if (linkText.includes("Lokasi")) {
      icon = "📍 "
    } else if (linkText.includes("Supplier")) {
      icon = "🏢 "
    } else if (linkText.includes("Pelanggan")) {
      icon = "👥 "
    } else if (linkText.includes("Stok Masuk")) {
      icon = "📈 "
    } else if (linkText.includes("Stok Keluar")) {
      icon = "📉 "
    } else if (linkText.includes("Stok Saat Ini")) {
      icon = "📊 "
    } else if (linkText.includes("Logout")) {
      icon = "🚪 "
    } else {
      icon = "→ "
    }

    link.innerHTML = icon + linkText
  })

  // Style table action links
  const actionLinks = document.querySelectorAll("table a")
  actionLinks.forEach((link) => {
    const linkText = link.textContent.trim().toLowerCase()

    if (linkText.includes("edit")) {
      link.classList.add("btn", "btn-edit")
      link.innerHTML = "✏️ Edit"
    } else if (linkText.includes("hapus")) {
      link.classList.add("btn", "btn-hapus")
      link.innerHTML = "🗑️ Hapus"
    }
  })
}

function addRippleEffects() {
  const buttons = document.querySelectorAll("button, .btn, .dashboard-list a")

  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      // Create ripple element
      const ripple = document.createElement("span")
      const rect = this.getBoundingClientRect()
      const size = Math.max(rect.width, rect.height)
      const x = e.clientX - rect.left - size / 2
      const y = e.clientY - rect.top - size / 2

      ripple.style.width = ripple.style.height = size + "px"
      ripple.style.left = x + "px"
      ripple.style.top = y + "px"
      ripple.style.position = "absolute"
      ripple.style.borderRadius = "50%"
      ripple.style.background = "rgba(255, 255, 255, 0.6)"
      ripple.style.transform = "scale(0)"
      ripple.style.animation = "ripple 0.6s linear"
      ripple.style.pointerEvents = "none"

      this.appendChild(ripple)

      // Remove ripple after animation
      setTimeout(() => {
        if (ripple.parentNode) {
          ripple.parentNode.removeChild(ripple)
        }
      }, 600)
    })
  })

  // Add ripple animation keyframes
  if (!document.querySelector("#ripple-styles")) {
    const style = document.createElement("style")
    style.id = "ripple-styles"
    style.textContent = `
      @keyframes ripple {
        to {
          transform: scale(4);
          opacity: 0;
        }
      }
    `
    document.head.appendChild(style)
  }
}

function enhanceFormInputs() {
  const inputs = document.querySelectorAll("input, textarea, select")

  inputs.forEach((input) => {
    // Add input validation styling
    input.addEventListener("invalid", function () {
      this.style.borderColor = "var(--md-sys-color-error)"
      this.style.boxShadow = "0 0 0 2px rgba(186, 26, 26, 0.2)"
    })

    input.addEventListener("input", function () {
      if (this.validity.valid) {
        this.style.borderColor = "var(--md-sys-color-outline)"
        this.style.boxShadow = "none"
      }
    })
  })
}

function addLoadingStates() {
  const forms = document.querySelectorAll("form")

  forms.forEach((form) => {
    form.addEventListener("submit", function () {
      const submitButton = this.querySelector('button[type="submit"], input[type="submit"]')
      if (submitButton) {
        submitButton.disabled = true
        const originalText = submitButton.textContent || submitButton.value

        if (submitButton.tagName === "INPUT") {
          submitButton.value = "⏳ Loading..."
        } else {
          submitButton.innerHTML = "⏳ Loading..."
        }

        submitButton.style.opacity = "0.7"

        // Re-enable after 5 seconds (fallback)
        setTimeout(() => {
          submitButton.disabled = false
          if (submitButton.tagName === "INPUT") {
            submitButton.value = originalText
          } else {
            submitButton.innerHTML = originalText
          }
          submitButton.style.opacity = "1"
        }, 5000)
      }
    })
  })
}

function enhanceDataTables() {
  const tables = document.querySelectorAll("table")

  tables.forEach((table) => {
    // Add hover effects to rows
    const rows = table.querySelectorAll("tbody tr")
    rows.forEach((row) => {
      row.addEventListener("mouseenter", function () {
        this.style.backgroundColor = "var(--md-sys-color-surface-container-highest)"
        this.style.transform = "scale(1.005)"
        this.style.transition = "all 0.2s ease"
      })

      row.addEventListener("mouseleave", function () {
        this.style.backgroundColor = ""
        this.style.transform = ""
      })
    })
  })
}

function addKeyboardNavigation() {
  // Add keyboard navigation for dashboard cards
  const dashboardLinks = document.querySelectorAll(".dashboard-list a")

  dashboardLinks.forEach((link, index) => {
    link.setAttribute("tabindex", "0")

    link.addEventListener("keydown", function (e) {
      switch (e.key) {
        case "ArrowDown":
        case "ArrowRight":
          e.preventDefault()
          const nextIndex = (index + 1) % dashboardLinks.length
          dashboardLinks[nextIndex].focus()
          break

        case "ArrowUp":
        case "ArrowLeft":
          e.preventDefault()
          const prevIndex = (index - 1 + dashboardLinks.length) % dashboardLinks.length
          dashboardLinks[prevIndex].focus()
          break

        case "Enter":
        case " ":
          e.preventDefault()
          this.click()
          break
      }
    })
  })
}

// Add keyboard shortcut for theme toggle (Ctrl/Cmd + Shift + T)
document.addEventListener("keydown", (e) => {
  if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === "T") {
    e.preventDefault()
    toggleTheme()
  }

  // Existing escape key handler
  if (e.key === "Escape") {
    const activeElement = document.activeElement
    if (activeElement && (activeElement.tagName === "INPUT" || activeElement.tagName === "TEXTAREA")) {
      activeElement.blur()
    }
  }
})

// Utility function to show toast notifications
function showToast(message, type) {
  type = type || "info"

  const toast = document.createElement("div")
  toast.textContent = message

  // Style the toast
  toast.style.position = "fixed"
  toast.style.top = "20px"
  toast.style.right = "20px"
  toast.style.padding = "16px 24px"
  toast.style.borderRadius = "var(--md-sys-shape-corner-medium)"
  toast.style.boxShadow = "var(--md-sys-elevation-level3)"
  toast.style.zIndex = "5000"
  toast.style.maxWidth = "300px"
  toast.style.fontFamily = "Roboto, sans-serif"
  toast.style.fontWeight = "500"

  // Set colors based on type
  switch (type) {
    case "success":
      toast.style.backgroundColor = "var(--md-sys-color-tertiary-container)"
      toast.style.color = "var(--md-sys-color-on-tertiary-container)"
      break
    case "error":
      toast.style.backgroundColor = "var(--md-sys-color-error-container)"
      toast.style.color = "var(--md-sys-color-on-error-container)"
      break
    default:
      toast.style.backgroundColor = "var(--md-sys-color-surface-container-high)"
      toast.style.color = "var(--md-sys-color-on-surface)"
  }

  document.body.appendChild(toast)

  // Animate in
  toast.style.transform = "translateX(100%)"
  toast.style.transition = "transform 0.3s ease"
  setTimeout(() => {
    toast.style.transform = "translateX(0)"
  }, 10)

  // Remove toast after 3 seconds
  setTimeout(() => {
    toast.style.transform = "translateX(100%)"
    setTimeout(() => {
      if (toast.parentNode) {
        toast.parentNode.removeChild(toast)
      }
    }, 300)
  }, 3000)
}

// Make functions available globally
window.MaterialDesign = {
  showToast: showToast,
  initializeComponents: initializeComponents,
  toggleTheme: toggleTheme,
  initializeTheme: initializeTheme,
}
