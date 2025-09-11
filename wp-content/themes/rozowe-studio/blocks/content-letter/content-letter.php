<?php
/**
 * Content with Letter Block
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
function rozowe_studio_content_letter_block_assets() {
    // Editor script
    wp_register_script(
        'rozowe-studio-content-letter-editor',
        get_template_directory_uri() . '/blocks/content-letter/content-letter-editor.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor'),
        '1.0.0',
        true
    );
}
add_action('enqueue_block_editor_assets', 'rozowe_studio_content_letter_block_assets');

/**
 * Register Content with Letter Block
 */
function rozowe_studio_register_content_letter_block() {
    // Check if Gutenberg is available
    if (!function_exists('register_block_type')) {
        return;
    }

    // Register the block
    register_block_type('rozowe-studio/content-letter', array(
        'editor_script' => 'rozowe-studio-content-letter-editor',
        'render_callback' => 'rozowe_studio_render_content_letter_block',
        'attributes' => array(
            'letter' => array(
                'type' => 'string',
                'default' => '',
            ),
            'content' => array(
                'type' => 'string',
                'default' => '',
            ),
            'linkUrl' => array(
                'type' => 'string',
                'default' => '',
            ),
            'linkText' => array(
                'type' => 'string',
                'default' => 'Zobacz więcej',
            ),
            'linkIcon' => array(
                'type' => 'string',
                'default' => '<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.16699 10.5H15.8337M15.8337 10.5L12.5003 13.8333M15.8337 10.5L12.5003 7.16663" stroke="#221516" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            ),
            'darkBackground' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'contentOnRight' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'backgroundPositionCenter' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'backgroundFullWidth' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'backgroundImage' => array(
                'type' => 'object',
                'default' => null,
            ),
        ),
    ));
}
add_action('init', 'rozowe_studio_register_content_letter_block');

/**
 * Render Content with Letter Block
 */
function rozowe_studio_render_content_letter_block($attributes, $content) {
    $letter = $attributes['letter'] ?? '';
    $block_content = $attributes['content'] ?? '';
    $link_url = $attributes['linkUrl'] ?? '';
    $link_text = $attributes['linkText'] ?? 'Zobacz więcej';
    $link_icon = $attributes['linkIcon'] ?? '<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.16699 10.5H15.8337M15.8337 10.5L12.5003 13.8333M15.8337 10.5L12.5003 7.16663" stroke="#221516" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $dark_background = $attributes['darkBackground'] ?? false;
    $content_on_right = $attributes['contentOnRight'] ?? false;
    $background_position_center = $attributes['backgroundPositionCenter'] ?? false;
    $background_full_width = $attributes['backgroundFullWidth'] ?? false;
    $background_image = $attributes['backgroundImage'] ?? null;

    // Get background image URL
    $background_url = $background_image ? $background_image['url'] : '';

    // Determine classes based on settings
    $background_class = $dark_background ? 'bg-burgundy-500' : 'bg-white-200';
    $text_class = $dark_background ? 'text-white-100' : 'text-black-600';
    
    // Set grid column class based on background position
    if ($background_position_center) {
        $grid_column_class = 'grid-col-7';
    } else {
        $grid_column_class = 'grid-col-4';
    }
    
    $background_position_class = $background_position_center ? 'bg-position-center' : '';
    $background_full_width_class = $background_full_width ? 'bg-full-width' : '';
    $content_right_class = $content_on_right ? 'content-right' : '';

    // Build the content HTML
    $content_html = '';
    
    // Add letter if provided
    if ($letter) {
        $content_html .= '<div class="content-letter-bg">' . esc_html($letter) . '</div>';
    }
    
    // Add content wrapper
    $content_html .= '<div class="content-letter-content"><p>';
    
    // Add content from attributes
    if ($block_content) {
        $content_html .= wp_kses_post($block_content);
    }
    
    $content_html .= '</p></div>'; // Close content-letter-content
    
    // Add link if provided (outside of content-letter-content)
    if ($link_url) {
        $icon_html = $link_icon ? '<span class="btn-icon">' . wp_kses($link_icon, array(
            'svg' => array(
                'width' => array(),
                'height' => array(),
                'viewBox' => array(),
                'fill' => array(),
                'xmlns' => array(),
                'class' => array(),
                'style' => array()
            ),
            'path' => array(
                'd' => array(),
                'fill' => array(),
                'stroke' => array(),
                'stroke-width' => array(),
                'stroke-linecap' => array(),
                'stroke-linejoin' => array()
            ),
            'circle' => array(
                'cx' => array(),
                'cy' => array(),
                'r' => array(),
                'fill' => array(),
                'stroke' => array()
            ),
            'rect' => array(
                'x' => array(),
                'y' => array(),
                'width' => array(),
                'height' => array(),
                'fill' => array(),
                'stroke' => array()
            ),
            'line' => array(
                'x1' => array(),
                'y1' => array(),
                'x2' => array(),
                'y2' => array(),
                'stroke' => array(),
                'stroke-width' => array()
            )
        )) . '</span>' : '';
        
        $content_html .= '<div class="content-letter-link"><a href="' . esc_url($link_url) . '" class="btn"><span class="btn-text">' . esc_html($link_text) . '</span>' . $icon_html . '</a></div>';
    }

    // Build the final HTML
    $block_classes = 'content-letter-block ' . $background_class . ' ' . $text_class . ' ' . $background_position_class . ' ' . $background_full_width_class . ' ' . $content_right_class;
    $block_style = $background_url ? ' style="background-image: url(\'' . esc_url($background_url) . '\');"' : '';
    
    $block_html = '<div class="' . esc_attr($block_classes) . '"' . $block_style . '>';
    $block_html .= '<div class="container">';
    $block_html .= '<div class="grid">';
    
    // Add empty div if content is on the right
    if ($content_on_right) {
        // Adjust empty div width based on content width
        if ($background_position_center) {
            $block_html .= '<div class="grid-col-5"></div>'; // 5 empty columns for 7-column content
        } else {
            $block_html .= '<div class="grid-col-8"></div>'; // 8 empty columns for 4-column content
        }
    }
    
    $block_html .= '<div class="' . esc_attr($grid_column_class) . '">';
    $block_html .= '<div class="content-letter-wrapper">';
    $block_html .= $content_html;
    $block_html .= '</div>'; // Close content-letter-wrapper
    $block_html .= '</div>'; // Close grid column
    $block_html .= '</div>'; // Close grid
    $block_html .= '</div>'; // Close container
    $block_html .= '</div>'; // Close content-letter-block

    return $block_html;
}
