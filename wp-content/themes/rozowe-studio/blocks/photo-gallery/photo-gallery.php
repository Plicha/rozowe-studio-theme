<?php
/**
 * Photo Gallery Block
 * 
 * @package Różowe_Studio
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue block assets
 */
function rozowe_studio_photo_gallery_block_assets() {
    // Editor script
    wp_register_script(
        'rozowe-studio-photo-gallery-editor',
        get_template_directory_uri() . '/blocks/photo-gallery/photo-gallery-editor.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor'),
        '1.0.0',
        true
    );

    // Editor style
    wp_register_style(
        'rozowe-studio-photo-gallery-editor',
        get_template_directory_uri() . '/blocks/photo-gallery/photo-gallery-editor.css',
        array('wp-edit-blocks'),
        '1.0.0'
    );

    // Frontend styles are included in main.scss

    // Frontend script for fslightbox
    wp_register_script(
        'fslightbox',
        get_template_directory_uri() . '/blocks/photo-gallery/fslightbox.js',
        array(),
        '1.0.0',
        false // Load in header to ensure it's available
    );

    // Frontend script for gallery functionality
    wp_register_script(
        'rozowe-studio-photo-gallery-frontend',
        get_template_directory_uri() . '/blocks/photo-gallery/photo-gallery-frontend.js',
        array('fslightbox'),
        '1.0.0',
        true
    );
}
add_action('enqueue_block_editor_assets', 'rozowe_studio_photo_gallery_block_assets');

/**
 * Enqueue frontend assets
 */
function rozowe_studio_photo_gallery_frontend_assets() {
    // Frontend script for fslightbox
    wp_enqueue_script(
        'fslightbox',
        get_template_directory_uri() . '/blocks/photo-gallery/fslightbox.js',
        array(),
        '1.0.0',
        false // Load in header to ensure it's available
    );

    // Frontend script for gallery functionality
    wp_enqueue_script(
        'rozowe-studio-photo-gallery-frontend',
        get_template_directory_uri() . '/blocks/photo-gallery/photo-gallery-frontend.js',
        array('fslightbox'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'rozowe_studio_photo_gallery_frontend_assets');

/**
 * Register Photo Gallery Block
 */
function rozowe_studio_register_photo_gallery_block() {
    // Check if Gutenberg is available
    if (!function_exists('register_block_type')) {
        return;
    }

    // Register the block
    register_block_type('rozowe-studio/photo-gallery', array(
        'editor_script' => 'rozowe-studio-photo-gallery-editor',
        'editor_style'  => 'rozowe-studio-photo-gallery-editor',
        'script'        => 'rozowe-studio-photo-gallery-frontend',
        'render_callback' => 'rozowe_studio_render_photo_gallery_block',
        'attributes' => array(
            'images' => array(
                'type' => 'array',
                'default' => array(),
            ),
            'threeColumnLayout' => array(
                'type' => 'boolean',
                'default' => false,
            ),
        ),
    ));
}
add_action('init', 'rozowe_studio_register_photo_gallery_block');

/**
 * Render Photo Gallery Block
 */
function rozowe_studio_render_photo_gallery_block($attributes, $content) {
    $images = $attributes['images'] ?? array();
    $three_column_layout = $attributes['threeColumnLayout'] ?? false;
    
    if (empty($images)) {
        return '';
    }

    $output = '<div class="photo-gallery-block photo-gallery-fullwidth">';
    $output .= '<div class="photo-gallery-container' . ($three_column_layout ? ' photo-gallery-three-column' : '') . '">';
    
    if ($three_column_layout) {
        // Three column layout
        $row_count = 0;
        for ($i = 0; $i < count($images); $i += 3) {
            $row_count++;
            $is_odd_row = $row_count % 2 === 1;
            
            $output .= '<div class="photo-gallery-row">';
            
            if ($is_odd_row) {
                // Odd rows: 3 equal items
                for ($j = 0; $j < 3 && ($i + $j) < count($images); $j++) {
                    $image = $images[$i + $j];
                    $output .= '<div class="photo-gallery-item photo-gallery-item-three-equal">';
                    $output .= '<a href="' . esc_url($image['url']) . '" data-fslightbox="gallery">';
                    $output .= '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt'] ?? '') . '" />';
                    $output .= '</a>';
                    $output .= '</div>';
                }
            } else {
                // Even rows: 2 equal items
                for ($j = 0; $j < 2 && ($i + $j) < count($images); $j++) {
                    $image = $images[$i + $j];
                    $output .= '<div class="photo-gallery-item photo-gallery-item-two-equal">';
                    $output .= '<a href="' . esc_url($image['url']) . '" data-fslightbox="gallery">';
                    $output .= '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt'] ?? '') . '" />';
                    $output .= '</a>';
                    $output .= '</div>';
                }
            }
            
            $output .= '</div>';
        }
    } else {
        // Original two column layout
        $row_count = 0;
        for ($i = 0; $i < count($images); $i += 2) {
            $row_count++;
            $is_odd_row = $row_count % 2 === 1;
            
            $output .= '<div class="photo-gallery-row">';
            
            // First image in row
            $first_image = $images[$i];
            $first_class = $is_odd_row ? 'photo-gallery-item photo-gallery-item-small' : 'photo-gallery-item photo-gallery-item-large';
            $output .= '<div class="' . $first_class . '">';
            $output .= '<a href="' . esc_url($first_image['url']) . '" data-fslightbox="gallery">';
            $output .= '<img src="' . esc_url($first_image['url']) . '" alt="' . esc_attr($first_image['alt'] ?? '') . '" />';
            $output .= '</a>';
            $output .= '</div>';
            
            // Second image in row (if exists)
            if (isset($images[$i + 1])) {
                $second_image = $images[$i + 1];
                $second_class = $is_odd_row ? 'photo-gallery-item photo-gallery-item-large' : 'photo-gallery-item photo-gallery-item-small';
                $output .= '<div class="' . $second_class . '">';
                $output .= '<a href="' . esc_url($second_image['url']) . '" data-fslightbox="gallery">';
                $output .= '<img src="' . esc_url($second_image['url']) . '" alt="' . esc_attr($second_image['alt'] ?? '') . '" />';
                $output .= '</a>';
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
    }
    
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}
