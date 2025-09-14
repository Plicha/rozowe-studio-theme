<?php
/**
 * Photography Teaser Block
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
function rozowe_studio_photography_teaser_block_assets() {
    // Editor script
    wp_register_script(
        'rozowe-studio-photography-teaser-editor',
        get_template_directory_uri() . '/blocks/photography-teaser/photography-teaser-editor.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor'),
        '1.0.5',
        true
    );
}
add_action('enqueue_block_editor_assets', 'rozowe_studio_photography_teaser_block_assets');

/**
 * Register Photography Teaser Block
 */
function rozowe_studio_register_photography_teaser_block() {
    // Check if Gutenberg is available
    if (!function_exists('register_block_type')) {
        return;
    }

    // Register the block
    register_block_type('rozowe-studio/photography-teaser', array(
        'editor_script' => 'rozowe-studio-photography-teaser-editor',
        'render_callback' => 'rozowe_studio_render_photography_teaser_block',
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
            'contentColumnOnLeft' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'letterOnLeft' => array(
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
add_action('init', 'rozowe_studio_register_photography_teaser_block');

/**
 * Render Photography Teaser Block
 */
function rozowe_studio_render_photography_teaser_block($attributes, $content) {
    $letter = $attributes['letter'] ?? '';
    $block_content = $attributes['content'] ?? '';
    $link_url = $attributes['linkUrl'] ?? '';
    $link_text = $attributes['linkText'] ?? 'Zobacz więcej';
    $link_icon = $attributes['linkIcon'] ?? '<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.16699 10.5H15.8337M15.8337 10.5L12.5003 13.8333M15.8337 10.5L12.5003 7.16663" stroke="#221516" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $content_column_on_left = $attributes['contentColumnOnLeft'] ?? false;
    $letter_on_left = $attributes['letterOnLeft'] ?? false;
    $background_image = $attributes['backgroundImage'] ?? null;

    // Determine classes based on settings
    $content_left_class = $content_column_on_left ? 'content-column-left' : '';
    $letter_left_class = $letter_on_left ? 'letter-left' : '';

    // Build the content HTML
    $content_html = '';
    
    // Add letter if provided
    if ($letter) {
        $content_html .= '<div class="photography-teaser-letter">' . esc_html($letter) . '</div>';
    }
    
    // Add content wrapper
    $content_html .= '<div class="photography-teaser-content"><p>';
    
    // Add content from attributes
    if ($block_content) {
        $content_html .= wp_kses_post($block_content);
    }
    
    $content_html .= '</p></div>'; // Close photography-teaser-content
    
    // Add link if provided (outside of photography-teaser-content)
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
        
        $content_html .= '<div class="photography-teaser-link"><a href="' . esc_url($link_url) . '" class="btn"><span class="btn-text">' . esc_html($link_text) . '</span>' . $icon_html . '</a></div>';
    }

    // Get background image URL
    $background_url = $background_image ? $background_image['url'] : '';

    // Build the final HTML
    $block_classes = 'photography-teaser-block ' . $content_left_class . ' ' . $letter_left_class;
    $block_style = $background_url ? ' style="background-image: url(\'' . esc_url($background_url) . '\');"' : '';
    
    $block_html = '<div class="' . esc_attr($block_classes) . '"' . $block_style . '>';
    $block_html .= '<div class="container">';
    $block_html .= '<div class="photography-teaser-wrapper">';
    $block_html .= $content_html;
    $block_html .= '</div>'; // Close photography-teaser-wrapper
    $block_html .= '</div>'; // Close container
    $block_html .= '</div>'; // Close photography-teaser-block

    return $block_html;
}