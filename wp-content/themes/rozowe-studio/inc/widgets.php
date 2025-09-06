<?php
/**
 * Widget areas registration
 *
 * @package Różowe_Studio
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

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
