<?php
/**
 * Security enhancements
 *
 * @package Różowe_Studio
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Security enhancements
 */
function rozowe_studio_security_headers() {
    // Remove WordPress version
    remove_action('wp_head', 'wp_generator');
    
    // Remove unnecessary links
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'rozowe_studio_security_headers');
