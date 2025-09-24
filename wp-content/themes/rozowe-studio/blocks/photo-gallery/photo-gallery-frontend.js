(function() {
    'use strict';

    // Initialize fslightbox when DOM is ready
    function initPhotoGallery() {
        // Wait for fslightbox to be available
        if (typeof window.fsLightboxInstances !== 'undefined') {
            // Refresh fslightbox to scan for new elements
            if (typeof window.refreshFsLightbox === 'function') {
                window.refreshFsLightbox();
            }
            return;
        }
        
        // Wait for fslightbox to load
        var checkFslightbox = setInterval(function() {
            if (typeof window.fsLightboxInstances !== 'undefined') {
                clearInterval(checkFslightbox);
                // Refresh fslightbox to scan for new elements
                if (typeof window.refreshFsLightbox === 'function') {
                    window.refreshFsLightbox();
                }
            }
        }, 100);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPhotoGallery);
    } else {
        initPhotoGallery();
    }

    // Also initialize when the page is fully loaded
    window.addEventListener('load', function() {
        if (typeof window.refreshFsLightbox === 'function') {
            window.refreshFsLightbox();
        }
    });

})();
