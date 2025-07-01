// Material 3 Interactive Enhancements
document.addEventListener("DOMContentLoaded", () => {
  // Initialize the gradient background
  initializeGradientBackground()

  // Convert all links to Material 3 buttons
  convertLinksToButtons()

  // Add ripple effects to buttons
  addRippleEffects()

  // Enhance form interactions
  enhanceFormInputs()

  // Add loading states
  addLoadingStates()

  // Initialize table enhancements
  enhanceDataTables()

  // Add keyboard navigation
  addKeyboardNavigation()
})

function initializeGradientBackground() {
  // Create gradient background if it doesn't exist
  if (!document.querySelector(".gradient-background")) {
    const gradientBg = document.createElement("div")
    gradientBg.className = "gradient-background"
    document.body.insertBefore(gradientBg, document.body.firstChild)
  }

  // Add dynamic color changes based on time of day
  const hour = new Date().getHours()
  const gradientBg = document.querySelector(".gradient-background")

  if (hour >= 6 && hour < 12) {
    // Morning colors
    gradientBg.style.background = "linear-gradient(45deg, #E3F2FD, #F3E5F5, #FFF3E0)"
  } else if (hour >= 12 && hour < 18) {
    // Afternoon colors
    gradientBg.style.background = "linear-gradient(45deg, #E8F5E8, #FFF8E1, #FCE4EC)"
  } else {
    // Evening/Night colors
    gradientBg.style.background = "linear-gradient(45deg, #E1F5FE, #F1F8E9, #FFF3E0)"
  }
}

function convertLinksToButtons() {
  // Convert navigation links in dashboard to proper buttons
  const dashboardLinks = document.querySelectorAll(".dashboard-list a")
  dashboardLinks.forEach((link) => {
    // Add appropriate icons based on link text
    const linkText = link.textContent.trim()
    let icon = ""

    switch (true) {
      case linkText.includes("Produk"):
        icon = "📦 "
        break
      case linkText.includes("Lokasi"):
        icon = "📍 "
        break
      case linkText.includes("Supplier"):
        icon = "🏢 "
        break
      case linkText.includes("Pelanggan"):
        icon = "👥 "
        break
      case linkText.includes("Stok Masuk"):
        icon = "📈 "
        break
      case linkText.includes("Stok Keluar"):
        icon = "📉 "
        break
      case linkText.includes("Stok Saat Ini"):
        icon = "📊 "
        break
      case linkText.includes("Logout"):
        icon = "🚪 "
        link.classList.add("btn-secondary")
        break
      default:
        icon = "→ "
    }

    link.innerHTML = icon + linkText
  })

  // Convert table action links to proper buttons
  const actionLinks = document.querySelectorAll("table a")
  actionLinks.forEach((link) => {
    const linkText = link.textContent.trim().toLowerCase()

    if (linkText.includes("edit")) {
      link.classList.add("btn", "btn-edit")
      link.innerHTML = "✏️ Edit"
    } else if (linkText.includes("hapus") || linkText.includes("delete")) {
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
      ripple.classList.add("ripple-effect")

      // Add ripple styles
      ripple.style.position = "absolute"
      ripple.style.borderRadius = "50%"
      ripple.style.background = "rgba(255, 255, 255, 0.6)"
      ripple.style.transform = "scale(0)"
      ripple.style.animation = "ripple 0.6s linear"
      ripple.style.pointerEvents = "none"

      this.appendChild(ripple)

      // Remove ripple after animation
      setTimeout(() => {
        ripple.remove()
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
    // Add floating label effect
    const label = document.querySelector(`label[for="${input.id}"]`)
    if (label) {
      const wrapper = document.createElement("div")
      wrapper.className = "input-wrapper"
      wrapper.style.position = "relative"
      wrapper.style.marginBottom = "20px"

      input.parentNode.insertBefore(wrapper, input)
      wrapper.appendChild(label)
      wrapper.appendChild(input)

      // Style the floating label
      label.style.position = "absolute"
      label.style.left = "16px"
      label.style.top = input.value ? "8px" : "16px"
      label.style.fontSize = input.value ? "12px" : "16px"
      label.style.color = "var(--md-sys-color-primary)"
      label.style.transition = "all 0.2s ease"
      label.style.pointerEvents = "none"
      label.style.backgroundColor = "var(--md-sys-color-surface)"
      label.style.padding = "0 4px"

      // Add focus and blur handlers
      input.addEventListener("focus", () => {
        label.style.top = "8px"
        label.style.fontSize = "12px"
        label.style.color = "var(--md-sys-color-primary)"
      })

      input.addEventListener("blur", () => {
        if (!input.value) {
          label.style.top = "16px"
          label.style.fontSize = "16px"
          label.style.color = "var(--md-sys-color-on-surface-variant)"
        }
      })
    }

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
      const submitButton = this.querySelector('button[type="submit"]')
      if (submitButton) {
        submitButton.disabled = true
        submitButton.innerHTML = "⏳ Loading..."
        submitButton.style.opacity = "0.7"

        // Re-enable after 3 seconds (fallback)
        setTimeout(() => {
          submitButton.disabled = false
          submitButton.innerHTML = "Login"
          submitButton.style.opacity = "1"
        }, 3000)
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
        this.style.transform = "scale(1.01)"
        this.style.transition = "all 0.2s ease"
      })

      row.addEventListener("mouseleave", function () {
        this.style.backgroundColor = ""
        this.style.transform = ""
      })
    })

    // Add sorting indicators to headers (visual only)
    const headers = table.querySelectorAll("th")
    headers.forEach((header) => {
      if (header.textContent.trim() !== "Aksi") {
        header.style.cursor = "pointer"
        header.style.userSelect = "none"
        header.innerHTML += " ↕️"

        header.addEventListener("click", function () {
          // Visual feedback for sorting
          headers.forEach((h) => (h.style.backgroundColor = ""))
          this.style.backgroundColor = "var(--md-sys-color-primary-container)"

          setTimeout(() => {
            this.style.backgroundColor = ""
          }, 300)
        })
      }
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

  // Add escape key handler for forms
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      const activeElement = document.activeElement
      if (activeElement && (activeElement.tagName === "INPUT" || activeElement.tagName === "TEXTAREA")) {
        activeElement.blur()
      }
    }
  })
}

// Utility function to show toast notifications
function showToast(message, type = "info") {
  const toast = document.createElement("div")
  toast.className = `toast toast-${type}`
  toast.textContent = message

  // Style the toast
  toast.style.position = "fixed"
  toast.style.top = "20px"
  toast.style.right = "20px"
  toast.style.padding = "16px 24px"
  toast.style.borderRadius = "var(--md-sys-shape-corner-medium)"
  toast.style.boxShadow = "var(--md-sys-elevation-level3)"
  toast.style.zIndex = "1000"
  toast.style.maxWidth = "300px"
  toast.style.animation = "slideInRight 0.3s ease"

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

  // Remove toast after 3 seconds
  setTimeout(() => {
    toast.style.animation = "slideOutRight 0.3s ease"
    setTimeout(() => toast.remove(), 300)
  }, 3000)
}

// Add toast animation styles
const toastStyles = document.createElement("style")
toastStyles.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`
document.head.appendChild(toastStyles)

// Export functions for use in other scripts
window.MaterialDesign = {
  showToast,
  initializeGradientBackground,
  convertLinksToButtons,
  addRippleEffects,
}
