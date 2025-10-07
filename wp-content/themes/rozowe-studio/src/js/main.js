// Main JavaScript file for Różowe Studio theme

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Różowe Studio theme loaded');
    
    // Initialize components
    initNavigation();
    initForms();
    initBlocks();
    initStickyNavbar();
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

// Sticky navbar functionality
function initStickyNavbar() {
    const navbar = document.querySelector('.custom-navbar');
    
    console.log('initStickyNavbar called, navbar found:', navbar);
    
    if (!navbar) {
        console.log('Navbar not found!');
        return;
    }
    
    const navbarHeight = navbar.offsetHeight;
    console.log('Navbar height:', navbarHeight);
    
    function handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        console.log('Scroll position:', scrollTop, 'Navbar height:', navbarHeight);
        
        if (scrollTop > navbarHeight) {
            navbar.classList.add('is-sticky');
            console.log('Added is-sticky class');
        } else {
            navbar.classList.remove('is-sticky');
            console.log('Removed is-sticky class');
        }
    }
    
    // Throttle scroll events for better performance
    let ticking = false;
    function updateNavbar() {
        if (!ticking) {
            requestAnimationFrame(() => {
                handleScroll();
                ticking = false;
            });
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', updateNavbar);
    
    // Initial check
    handleScroll();
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
export { initNavigation, initForms, initBlocks, initStickyNavbar, debounce };
