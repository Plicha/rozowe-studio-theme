<?php
/**
 * Helper functions and utilities
 *
 * @package Różowe_Studio
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom excerpt length
 */
function rozowe_studio_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'rozowe_studio_excerpt_length');

/**
 * Custom excerpt more
 */
function rozowe_studio_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'rozowe_studio_excerpt_more');

/**
 * Add custom classes to body
 */
function rozowe_studio_body_classes($classes) {
    // Add class for no sidebar
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }
    
    return $classes;
}
add_filter('body_class', 'rozowe_studio_body_classes');

/**
 * Customize the main query
 */
function rozowe_studio_pre_get_posts($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Set posts per page
        if (is_home()) {
            $query->set('posts_per_page', 6);
        }
    }
}
add_action('pre_get_posts', 'rozowe_studio_pre_get_posts');

/**
 * Get Facebook URL from customizer
 */
function rozowe_studio_get_facebook_url() {
    return get_theme_mod('rozowe_studio_facebook_url', '');
}

/**
 * Get Instagram URL from customizer
 */
function rozowe_studio_get_instagram_url() {
    return get_theme_mod('rozowe_studio_instagram_url', '');
}

/**
 * Check if Facebook URL is set
 */
function rozowe_studio_has_facebook() {
    return !empty(get_theme_mod('rozowe_studio_facebook_url', ''));
}

/**
 * Check if Instagram URL is set
 */
function rozowe_studio_has_instagram() {
    return !empty(get_theme_mod('rozowe_studio_instagram_url', ''));
}
