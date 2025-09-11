<?php
/**
 * Theme setup and configuration
 *
 * @package Różowe_Studio
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function rozowe_studio_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'rozowe-studio'),
        'footer' => esc_html__('Footer Menu', 'rozowe-studio'),
    ));
    
    // Add image sizes
    add_image_size('rozowe-studio-featured', 800, 400, true);
    add_image_size('rozowe-studio-thumbnail', 300, 200, true);
}
add_action('after_setup_theme', 'rozowe_studio_setup');

/**
 * Add SVG support to WordPress
 */
function rozowe_studio_add_svg_support($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'rozowe_studio_add_svg_support');

/**
 * Fix SVG display in admin
 */
function rozowe_studio_fix_svg_display($response, $attachment, $meta) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
        $response['image'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150,
        );
        $response['thumb'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150,
        );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'rozowe_studio_fix_svg_display', 10, 3);

/**
 * Register image sizes
 */
function rozowe_studio_image_sizes() {
    add_image_size('hero-image', 1920, 1080, true);
    add_image_size('thumbnail-large', 400, 300, true);
}
add_action('after_setup_theme', 'rozowe_studio_image_sizes');
