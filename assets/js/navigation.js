// Enhanced Navigation JavaScript with Dark Mode
document.addEventListener("DOMContentLoaded", () => {
  // Initialize all navigation components
  initializeNavigation()
  initializeUserMenu()
  initializeSearch()
  initializeQuickActions()
  initializeActiveStates()
  initializeDarkMode()
  initializeNotifications()
  initializeSmoothnessEnhancements()
})

// Dark Mode Management
function initializeDarkMode() {
  const themeSwitch = document.getElementById("themeSwitch")
  const currentTheme = localStorage.getItem("theme") || "light"

  // Set initial theme
  document.documentElement.setAttribute("data-theme", currentTheme)

  // Update switch state
  if (themeSwitch) {
    if (currentTheme === "dark") {
      themeSwitch.classList.add("active")
    }

    themeSwitch.addEventListener("click", toggleDarkMode)
  }

  // Listen for system theme changes
  if (window.matchMedia) {
    const mediaQuery = window.matchMedia("(prefers-color-scheme: dark)")
    mediaQuery.addListener(handleSystemThemeChange)
  }
}

function toggleDarkMode() {
  const themeSwitch = document.getElementById("themeSwitch")
  const currentTheme = document.documentElement.getAttribute("data-theme")
  const newTheme = currentTheme === "dark" ? "light" : "dark"

  // Update theme
  document.documentElement.setAttribute("data-theme", newTheme)
  localStorage.setItem("theme", newTheme)

  // Update switch
  if (themeSwitch) {
    themeSwitch.classList.toggle("active", newTheme === "dark")
  }

  // Show notification
  const message = newTheme === "dark" ? "🌙 Dark mode enabled" : "☀️ Light mode enabled"
  showNotification(message, "info")

  // Smooth transition effect
  document.body.style.transition = "all 0.3s ease"
  setTimeout(() => {
    document.body.style.transition = ""
  }, 300)
}

function handleSystemThemeChange(e) {
  if (!localStorage.getItem("theme")) {
    const newTheme = e.matches ? "dark" : "light"
    document.documentElement.setAttribute("data-theme", newTheme)

    const themeSwitch = document.getElementById("themeSwitch")
    if (themeSwitch) {
      themeSwitch.classList.toggle("active", newTheme === "dark")
    }
  }
}

// Enhanced Navigation Functions
function initializeNavigation() {
  const menuToggle = document.getElementById("menuToggle")
  const offcanvasNav = document.getElementById("offcanvasNav")
  const offcanvasOverlay = document.getElementById("offcanvasOverlay")
  const offcanvasClose = document.getElementById("offcanvasClose")

  // Toggle offcanvas navigation
  if (menuToggle) {
    menuToggle.addEventListener("click", (e) => {
      e.preventDefault()
      toggleOffcanvas()
    })
  }

  // Close offcanvas when clicking overlay
  if (offcanvasOverlay) {
    offcanvasOverlay.addEventListener("click", () => {
      closeOffcanvas()
    })
  }

  // Close offcanvas when clicking close button
  if (offcanvasClose) {
    offcanvasClose.addEventListener("click", () => {
      closeOffcanvas()
    })
  }

  // Close offcanvas on escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeOffcanvas()
      closeSearch()
      closeUserDropdown()
    }
  })

  // Handle window resize
  window.addEventListener("resize", () => {
    if (window.innerWidth > 768) {
      closeOffcanvas()
    }
  })
}

function toggleOffcanvas() {
  const menuToggle = document.getElementById("menuToggle")
  const offcanvasNav = document.getElementById("offcanvasNav")
  const offcanvasOverlay = document.getElementById("offcanvasOverlay")

  if (offcanvasNav && offcanvasOverlay) {
    const isOpen = offcanvasNav.classList.contains("show")

    if (isOpen) {
      closeOffcanvas()
    } else {
      openOffcanvas()
    }
  }
}

function openOffcanvas() {
  const menuToggle = document.getElementById("menuToggle")
  const offcanvasNav = document.getElementById("offcanvasNav")
  const offcanvasOverlay = document.getElementById("offcanvasOverlay")

  if (menuToggle) menuToggle.classList.add("active")
  if (offcanvasNav) offcanvasNav.classList.add("show")
  if (offcanvasOverlay) offcanvasOverlay.classList.add("show")

  // Prevent body scroll
  document.body.style.overflow = "hidden"

  // Add staggered animation for nav items
  const navItems = document.querySelectorAll(".nav-link")
  navItems.forEach((item, index) => {
    item.style.animationDelay = `${index * 0.05}s`
    item.classList.add("slide-in")
  })
}

function closeOffcanvas() {
  const menuToggle = document.getElementById("menuToggle")
  const offcanvasNav = document.getElementById("offcanvasNav")
  const offcanvasOverlay = document.getElementById("offcanvasOverlay")

  if (menuToggle) menuToggle.classList.remove("active")
  if (offcanvasNav) offcanvasNav.classList.remove("show")
  if (offcanvasOverlay) offcanvasOverlay.classList.remove("show")

  // Restore body scroll
  document.body.style.overflow = ""

  // Remove animation classes
  const navItems = document.querySelectorAll(".nav-link")
  navItems.forEach((item) => {
    item.classList.remove("slide-in")
    item.style.animationDelay = ""
  })
}

// Enhanced User Menu
function initializeUserMenu() {
  const userMenuToggle = document.getElementById("userMenuToggle")
  const userDropdown = document.getElementById("userDropdown")
  const userMenu = document.querySelector(".user-menu")

  if (userMenuToggle && userDropdown) {
    userMenuToggle.addEventListener("click", (e) => {
      e.preventDefault()
      e.stopPropagation()
      toggleUserDropdown()
    })

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (userMenu && !userMenu.contains(e.target)) {
        closeUserDropdown()
      }
    })

    // Add hover effects for dropdown items
    const dropdownItems = userDropdown.querySelectorAll(".dropdown-item-enhanced")
    dropdownItems.forEach((item) => {
      item.addEventListener("mouseenter", () => {
        item.style.transform = "translateX(4px)"
      })
      item.addEventListener("mouseleave", () => {
        item.style.transform = "translateX(0)"
      })
    })
  }
}

function toggleUserDropdown() {
  const userDropdown = document.getElementById("userDropdown")
  const userMenu = document.querySelector(".user-menu")

  if (userDropdown && userMenu) {
    const isOpen = userDropdown.classList.contains("show")

    if (isOpen) {
      closeUserDropdown()
    } else {
      openUserDropdown()
    }
  }
}

function openUserDropdown() {
  const userDropdown = document.getElementById("userDropdown")
  const userMenu = document.querySelector(".user-menu")

  if (userDropdown) userDropdown.classList.add("show")
  if (userMenu) userMenu.classList.add("active")

  // Add staggered animation for dropdown items
  const items = userDropdown.querySelectorAll(".dropdown-item-enhanced")
  items.forEach((item, index) => {
    item.style.animationDelay = `${index * 0.05}s`
    item.style.animation = "slideInLeft 0.3s ease forwards"
  })
}

function closeUserDropdown() {
  const userDropdown = document.getElementById("userDropdown")
  const userMenu = document.querySelector(".user-menu")

  if (userDropdown) userDropdown.classList.remove("show")
  if (userMenu) userMenu.classList.remove("active")

  // Remove animations
  const items = userDropdown?.querySelectorAll(".dropdown-item-enhanced") || []
  items.forEach((item) => {
    item.style.animation = ""
    item.style.animationDelay = ""
  })
}

// Enhanced Search Functionality
function initializeSearch() {
  const quickSearchBtn = document.getElementById("quickSearch")
  const searchOverlay = document.getElementById("searchOverlay")
  const searchClose = document.getElementById("searchClose")
  const searchInput = document.getElementById("globalSearch")
  const searchBtn = document.querySelector(".search-btn")

  if (quickSearchBtn) {
    quickSearchBtn.addEventListener("click", (e) => {
      e.preventDefault()
      openSearch()
    })
  }

  if (searchClose) {
    searchClose.addEventListener("click", () => {
      closeSearch()
    })
  }

  if (searchOverlay) {
    searchOverlay.addEventListener("click", (e) => {
      if (e.target === searchOverlay) {
        closeSearch()
      }
    })
  }

  if (searchBtn) {
    searchBtn.addEventListener("click", () => {
      if (searchInput) {
        performSearch(searchInput.value)
      }
    })
  }

  // Enhanced search input handling
  if (searchInput) {
    let searchTimeout

    searchInput.addEventListener("input", function () {
      clearTimeout(searchTimeout)
      searchTimeout = setTimeout(() => {
        handleSearchInput(this.value)
      }, 300)
    })

    searchInput.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        e.preventDefault()
        performSearch(this.value)
      }
    })
  }

  // Handle suggestion tags
  const suggestionTags = document.querySelectorAll(".suggestion-tag")
  suggestionTags.forEach((tag) => {
    tag.addEventListener("click", function () {
      if (searchInput) {
        searchInput.value = this.textContent
        performSearch(this.textContent)
      }
    })
  })
}

function openSearch() {
  const searchOverlay = document.getElementById("searchOverlay")
  const searchInput = document.getElementById("globalSearch")

  if (searchOverlay) {
    searchOverlay.classList.add("show")

    // Focus on search input after animation
    setTimeout(() => {
      if (searchInput) {
        searchInput.focus()
        searchInput.select()
      }
    }, 300)
  }
}

function closeSearch() {
  const searchOverlay = document.getElementById("searchOverlay")

  if (searchOverlay) {
    searchOverlay.classList.remove("show")
  }
}

function handleSearchInput(query) {
  if (!query.trim()) return

  // Show loading state
  const searchBtn = document.querySelector(".search-btn")
  if (searchBtn) {
    searchBtn.textContent = "Searching..."
    searchBtn.disabled = true
  }

  // Simulate search suggestions (replace with actual API call)
  setTimeout(() => {
    if (searchBtn) {
      searchBtn.textContent = "Search"
      searchBtn.disabled = false
    }

    // Update suggestions based on query
    updateSearchSuggestions(query)
  }, 500)
}

function updateSearchSuggestions(query) {
  const suggestionItems = document.querySelector(".suggestion-items")
  if (!suggestionItems) return

  // Clear existing suggestions
  suggestionItems.innerHTML = ""

  // Add new suggestions based on query
  const suggestions = generateSuggestions(query)
  suggestions.forEach((suggestion) => {
    const tag = document.createElement("span")
    tag.className = "suggestion-tag"
    tag.textContent = suggestion
    tag.addEventListener("click", () => {
      const searchInput = document.getElementById("globalSearch")
      if (searchInput) {
        searchInput.value = suggestion
        performSearch(suggestion)
      }
    })
    suggestionItems.appendChild(tag)
  })
}

function generateSuggestions(query) {
  const allSuggestions = [
    "Stok Rendah",
    "Produk Terbaru",
    "Supplier Aktif",
    "Barang Masuk Hari Ini",
    "Stok Keluar",
    "Lokasi Gudang",
    "Laporan Bulanan",
    "Kategori Produk",
    "Supplier Terpercaya",
  ]

  if (!query) return allSuggestions.slice(0, 6)

  return allSuggestions.filter((suggestion) => suggestion.toLowerCase().includes(query.toLowerCase())).slice(0, 6)
}

function performSearch(query) {
  if (!query.trim()) {
    showNotification("Please enter a search term", "warning")
    return
  }

  console.log("Performing search for:", query)

  // Show search notification
  showNotification(`🔍 Searching for "${query}"...`, "info")

  // Simulate search results (replace with actual search implementation)
  setTimeout(() => {
    showNotification(`Found results for "${query}"`, "success")
    closeSearch()
  }, 1000)
}

// Enhanced Quick Actions
function initializeQuickActions() {
  const quickNotifications = document.getElementById("quickNotifications")

  if (quickNotifications) {
    quickNotifications.addEventListener("click", (e) => {
      e.preventDefault()
      toggleNotifications()
    })
  }
}

// Notifications System
function initializeNotifications() {
  // Simulate some notifications
  const notifications = [
    { id: 1, title: "Low Stock Alert", message: "Product ABC is running low", type: "warning", time: "2 min ago" },
    { id: 2, title: "New Stock Arrival", message: "50 units of XYZ received", type: "success", time: "1 hour ago" },
    { id: 3, title: "System Update", message: "New features available", type: "info", time: "3 hours ago" },
  ]

  // Update notification badge
  updateNotificationBadge(notifications.length)
}

function toggleNotifications() {
  // This would open a notifications panel
  showNotification("📬 Notifications panel would open here", "info")
}

function updateNotificationBadge(count) {
  const badge = document.querySelector(".notification-badge")
  if (badge) {
    badge.textContent = count
    badge.style.display = count > 0 ? "block" : "none"
  }
}

// Active States Management
function initializeActiveStates() {
  const currentPath = window.location.pathname
  const navLinks = document.querySelectorAll(".nav-link")

  navLinks.forEach((link) => {
    const linkPath = new URL(link.href).pathname
    const linkFile = linkPath.split("/").pop()
    const currentFile = currentPath.split("/").pop()

    if (
      currentPath === linkPath ||
      currentFile === linkFile ||
      (linkFile === "index.php" && currentPath.includes(linkPath.replace("/index.php", "")))
    ) {
      link.classList.add("active")
      link.setAttribute("aria-current", "page")
    }
  })
}

// Smoothness Enhancements
function initializeSmoothnessEnhancements() {
  // Add smooth scrolling
  document.documentElement.style.scrollBehavior = "smooth"

  // Add intersection observer for animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1"
        entry.target.style.transform = "translateY(0)"
      }
    })
  }, observerOptions)

  // Observe elements that should animate in
  const animateElements = document.querySelectorAll(".nav-link, .quick-action-card")
  animateElements.forEach((el) => {
    el.style.opacity = "0"
    el.style.transform = "translateY(20px)"
    el.style.transition = "opacity 0.6s ease, transform 0.6s ease"
    observer.observe(el)
  })

  // Add loading states for buttons
  const buttons = document.querySelectorAll("button, .btn")
  buttons.forEach((button) => {
    button.addEventListener("click", function () {
      if (!this.disabled) {
        this.style.transform = "scale(0.95)"
        setTimeout(() => {
          this.style.transform = ""
        }, 150)
      }
    })
  })

  // Add ripple effect to clickable elements
  addRippleEffect()
}

function addRippleEffect() {
  const rippleElements = document.querySelectorAll(
    ".nav-link, .quick-action-btn, .dropdown-item-enhanced, .theme-switch",
  )

  rippleElements.forEach((element) => {
    element.addEventListener("click", function (e) {
      const ripple = document.createElement("span")
      const rect = this.getBoundingClientRect()
      const size = Math.max(rect.width, rect.height)
      const x = e.clientX - rect.left - size / 2
      const y = e.clientY - rect.top - size / 2

      ripple.style.cssText = `
        position: absolute;
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
        z-index: 1;
      `

      // Ensure element has relative positioning
      if (getComputedStyle(this).position === "static") {
        this.style.position = "relative"
      }

      this.appendChild(ripple)

      setTimeout(() => {
        ripple.remove()
      }, 600)
    })
  })
}

// Utility Functions
function getRelativePath(path) {
  const currentPath = window.location.pathname
  const depth = (currentPath.match(/\//g) || []).length - 1

  let relativePath = ""
  for (let i = 0; i < depth - 1; i++) {
    relativePath += "../"
  }

  return relativePath + path
}

// Enhanced Notification System
function showNotification(message, type = "info", duration = 4000) {
  // Remove existing notifications of the same type
  const existingNotifications = document.querySelectorAll(`.notification-${type}`)
  existingNotifications.forEach((notification) => {
    removeNotification(notification)
  })

  // Create notification element
  const notification = document.createElement("div")
  notification.className = `notification notification-${type}`
  notification.innerHTML = `
    <div class="notification-content">
      <span class="notification-icon">${getNotificationIcon(type)}</span>
      <div class="notification-text">
        <span class="notification-message">${message}</span>
      </div>
      <button class="notification-close" aria-label="Close notification">✕</button>
    </div>
  `

  // Add enhanced styles
  notification.style.cssText = `
    position: fixed;
    top: 90px;
    right: 20px;
    background: linear-gradient(135deg, ${getNotificationColor(type)} 0%, ${getNotificationColor(type, true)} 100%);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 16px;
    max-width: 350px;
    min-width: 300px;
    z-index: 2000;
    transform: translateX(100%);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    color: white;
  `

  document.body.appendChild(notification)

  // Animate in
  setTimeout(() => {
    notification.style.transform = "translateX(0)"
  }, 100)

  // Handle close button
  const closeBtn = notification.querySelector(".notification-close")
  closeBtn.addEventListener("click", () => {
    removeNotification(notification)
  })

  // Auto remove after duration
  setTimeout(() => {
    removeNotification(notification)
  }, duration)

  // Add progress bar
  const progressBar = document.createElement("div")
  progressBar.style.cssText = `
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 0 0 12px 12px;
    animation: progress ${duration}ms linear;
  `
  notification.appendChild(progressBar)
}

function removeNotification(notification) {
  if (!notification || !notification.parentNode) return

  notification.style.transform = "translateX(100%)"
  notification.style.opacity = "0"

  setTimeout(() => {
    if (notification.parentNode) {
      notification.parentNode.removeChild(notification)
    }
  }, 300)
}

function getNotificationIcon(type) {
  const icons = {
    success: "✅",
    error: "❌",
    warning: "⚠️",
    info: "ℹ️",
  }
  return icons[type] || icons.info
}

function getNotificationColor(type, darker = false) {
  const colors = {
    success: darker ? "rgba(76, 175, 80, 0.9)" : "rgba(76, 175, 80, 0.95)",
    error: darker ? "rgba(244, 67, 54, 0.9)" : "rgba(244, 67, 54, 0.95)",
    warning: darker ? "rgba(255, 152, 0, 0.9)" : "rgba(255, 152, 0, 0.95)",
    info: darker ? "rgba(33, 150, 243, 0.9)" : "rgba(33, 150, 243, 0.95)",
  }
  return colors[type] || colors.info
}

// Keyboard Shortcuts
document.addEventListener("keydown", (e) => {
  // Dark mode toggle (Ctrl/Cmd + Shift + D)
  if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === "D") {
    e.preventDefault()
    toggleDarkMode()
  }

  // Search (Ctrl/Cmd + K)
  if ((e.ctrlKey || e.metaKey) && e.key === "k") {
    e.preventDefault()
    openSearch()
  }

  // Navigation toggle (Ctrl/Cmd + B)
  if ((e.ctrlKey || e.metaKey) && e.key === "b") {
    e.preventDefault()
    toggleOffcanvas()
  }
})

// Add CSS animations
const style = document.createElement("style")
style.textContent = `
  .slide-in {
    animation: slideInLeft 0.3s ease forwards;
  }
  
  @keyframes slideInLeft {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  @keyframes ripple {
    to {
      transform: scale(2);
      opacity: 0;
    }
  }
  
  @keyframes progress {
    from {
      width: 100%;
    }
    to {
      width: 0%;
    }
  }
  
  .notification-content {
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }
  
  .notification-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  
  .notification-message {
    font-size: 14px;
    font-weight: 500;
    line-height: 1.4;
  }
  
  .notification-close {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.8);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-size: 12px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .notification-close:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
  }
  
  /* Enhanced hover effects */
  .nav-link, .quick-action-btn, .dropdown-item-enhanced {
    position: relative;
    overflow: hidden;
  }
  
  /* Smooth focus indicators */
  .nav-link:focus-visible,
  .quick-action-btn:focus-visible,
  .dropdown-item-enhanced:focus-visible {
    outline: 2px solid var(--md-sys-color-primary);
    outline-offset: 2px;
  }
  
  /* Loading states */
  .loading {
    position: relative;
    pointer-events: none;
  }
  
  .loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  /* Reduced motion support */
  @media (prefers-reduced-motion: reduce) {
    * {
      animation-duration: 0.01ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.01ms !important;
    }
  }
`
document.head.appendChild(style)

// Initialize welcome message
setTimeout(() => {
  if (sessionStorage.getItem("welcomed") !== "true") {
    showNotification("🎉 Welcome to Stock Control Gudang! Navigation enhanced with dark mode support.", "success", 6000)
    sessionStorage.setItem("welcomed", "true")
  }
}, 1000)

// Performance monitoring
if (window.performance) {
  window.addEventListener("load", () => {
    const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart
    if (loadTime > 3000) {
      console.warn("Page load time is slow:", loadTime + "ms")
    }
  })
}
