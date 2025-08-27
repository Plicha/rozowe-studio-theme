<?php
/**
 * Różowe Studio functions and definitions
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
    add_theme_support('customize-selective-refresh-widgets');
    
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
 * Enqueue scripts and styles
 */
function rozowe_studio_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('rozowe-studio-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue Google Fonts (optional)
    wp_enqueue_style('rozowe-studio-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', array(), null);
    
    // Enqueue main JavaScript
    wp_enqueue_script('rozowe-studio-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('rozowe-studio-script', 'rozowe_studio_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('rozowe_studio_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'rozowe_studio_scripts');

/**
 * Register widget areas
 */
function rozowe_studio_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'rozowe-studio'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'rozowe-studio'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Widget Area', 'rozowe-studio'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here.', 'rozowe-studio'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'rozowe_studio_widgets_init');

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

/**
 * Add customizer support
 */
function rozowe_studio_customize_register($wp_customize) {
    // Add section for theme options
    $wp_customize->add_section('rozowe_studio_options', array(
        'title'    => __('Theme Options', 'rozowe-studio'),
        'priority' => 30,
    ));
    
    // Add setting for primary color
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#ff69b4',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label'    => __('Primary Color', 'rozowe-studio'),
        'section'  => 'rozowe_studio_options',
        'settings' => 'primary_color',
    )));
}
add_action('customize_register', 'rozowe_studio_customize_register');

/**
 * Fallback menu function
 */
function rozowe_studio_fallback_menu() {
    echo '<ul id="primary-menu" class="nav-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'rozowe-studio') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about/')) . '">' . esc_html__('About', 'rozowe-studio') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact/')) . '">' . esc_html__('Contact', 'rozowe-studio') . '</a></li>';
    echo '</ul>';
} 