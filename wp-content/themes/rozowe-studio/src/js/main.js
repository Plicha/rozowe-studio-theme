// Main JavaScript file for Różowe Studio theme

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Różowe Studio theme loaded');
    
    // Initialize components
    initNavigation();
    initForms();
    initBlocks();
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
export { initNavigation, initForms, initBlocks, debounce };
