// Main JavaScript file for Różowe Studio theme

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Różowe Studio theme loaded');
    
    // Initialize components
    initNavigation();
    initForms();
    initBlocks();
    initMobileNavbar();
});

// Navigation functionality
function initNavigation() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.main-navigation ul');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            navToggle.classList.toggle('active');
        });
    }
}

// Form functionality
function initForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Add form validation or AJAX submission here
            console.log('Form submitted:', form);
        });
    });
}

// Gutenberg blocks functionality
function initBlocks() {
    // Add any custom JavaScript for Gutenberg blocks here
    const blocks = document.querySelectorAll('.wp-block-group, .wp-block-button');
    
    blocks.forEach(block => {
        // Add any block-specific functionality
        console.log('Block initialized:', block);
    });
}

// Mobile navbar functionality
function initMobileNavbar() {
    const navbarToggle = document.querySelector('.navbar-toggle');
    const mobileMenu = document.querySelector('.navbar-mobile-menu');
    const mobileOverlay = document.querySelector('.navbar-mobile-overlay');
    
    if (!navbarToggle || !mobileMenu || !mobileOverlay) return;
    
    function openMobileMenu() {
        navbarToggle.classList.add('active');
        mobileMenu.classList.add('active');
        mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        navbarToggle.classList.remove('active');
        mobileMenu.classList.remove('active');
        mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Toggle mobile menu
    navbarToggle.addEventListener('click', function() {
        if (mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    });
    
    // Close on overlay click
    mobileOverlay.addEventListener('click', closeMobileMenu);
    
    // Close on menu item click
    const menuItems = mobileMenu.querySelectorAll('.navbar-item');
    menuItems.forEach(item => {
        item.addEventListener('click', closeMobileMenu);
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        }
    });
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export for use in other modules
export { initNavigation, initForms, initBlocks, initMobileNavbar, debounce };
