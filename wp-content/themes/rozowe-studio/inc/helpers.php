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
