/**
 * Enqueue scripts and styles
 */
function rozowe_studio_scripts() {
    $theme_version = wp_get_theme()->get('Version');
    
    // Development vs Production
    if (WP_DEBUG && file_exists(get_template_directory() . '/dist/css/main.css')) {
        // Development - use Parcel dev server
        wp_enqueue_style('rozowe-studio-style', 'http://localhost:1234/main.scss', array(), $theme_version);
        wp_enqueue_script('rozowe-studio-js', 'http://localhost:1235/main.js', array(), $theme_version, true);
    } else {
        // Production - use built files
        $css_file = get_template_directory() . '/dist/css/main.css';
        $js_file = get_template_directory() . '/dist/js/main.js';
        
        if (file_exists($css_file)) {
            wp_enqueue_style('rozowe-studio-style', get_template_directory_uri() . '/dist/css/main.css', array(), $theme_version);
        } else {
            // Fallback to original style.css
            wp_enqueue_style('rozowe-studio-style', get_stylesheet_uri(), array(), $theme_version);
        }
        
        if (file_exists($js_file)) {
            wp_enqueue_script('rozowe-studio-js', get_template_directory_uri() . '/dist/js/main.js', array(), $theme_version, true);
        }
    }
    
    // Enqueue Google Fonts
    wp_enqueue_style('rozowe-studio-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', array(), null);
    
    // Localize script for AJAX
    wp_localize_script('rozowe-studio-js', 'rozowe_studio_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('rozowe_studio_nonce'),
    ));
}
