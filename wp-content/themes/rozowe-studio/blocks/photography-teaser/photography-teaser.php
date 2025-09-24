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
        '1.0.7',
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
            'sectionTitle' => array(
                'type' => 'string',
                'default' => '',
            ),
            'showSocialMedia' => array(
                'type' => 'boolean',
                'default' => false,
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
    $section_title = $attributes['sectionTitle'] ?? '';
    $show_social_media = $attributes['showSocialMedia'] ?? false;

    // Determine classes based on settings
    $content_left_class = $content_column_on_left ? 'content-column-left' : '';
    $letter_left_class = $letter_on_left ? 'letter-left' : '';

    // Get background image URL
    $background_url = $background_image ? $background_image['url'] : '';

    // Build the final HTML
    $block_classes = 'photography-teaser-block bg-white-200 ' . $content_left_class . ' ' . $letter_left_class;
    
    $block_html = '<div class="' . esc_attr($block_classes) . '">';
    $block_html .= '<div class="container">';
    $block_html .= '<div class="grid">';
    
    // Image column (6 columns on desktop, full width on mobile/tablet)
    $block_html .= '<div class="grid-col-6 photography-teaser-image-column">';
    if ($background_url) {
        $block_html .= '<img src="' . esc_url($background_url) . '" alt="' . esc_attr($background_image['alt'] ?? '') . '" class="photography-teaser-image">';
    }
    $block_html .= '</div>'; // Close photography-teaser-image-column
    
    // Content column (6 columns on desktop, full width on mobile/tablet)
    $block_html .= '<div class="grid-col-6 photography-teaser-content-column">';
    
    // Add letter if provided and letter-on-left is true
    if ($letter && $letter_on_left) {
        $block_html .= '<div class="photography-teaser-letter">' . esc_html($letter) . '</div>';
    }
    
    $block_html .= '<div class="photography-teaser-content-wrapper">';
    
    // Add section title if provided (visible only on tablet and mobile)
    if ($section_title) {
        $block_html .= '<h2 class="photography-teaser-section-title">' . esc_html($section_title) . '</h2>';
    }
    
    // Add letter if provided and letter-on-left is false
    if ($letter && !$letter_on_left) {
        $block_html .= '<div class="photography-teaser-letter">' . esc_html($letter) . '</div>';
    }
    
    // Add content
    if ($block_content) {
        $block_html .= '<div class="photography-teaser-content">' . wp_kses_post($block_content) . '</div>';
    }
    
    // Add link if provided
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
        
        $block_html .= '<div class="photography-teaser-link"><a href="' . esc_url($link_url) . '" class="btn"><span class="btn-text">' . esc_html($link_text) . '</span>' . $icon_html . '</a></div>';
    }
    
    // Add social media links if enabled
    if ($show_social_media) {
        $block_html .= '<div class="photography-teaser-social-media"><p>Zamówienia:</p>';
        
        // Instagram link
        if (function_exists('rozowe_studio_has_instagram') && rozowe_studio_has_instagram()) {
            $instagram_url = rozowe_studio_get_instagram_url();
            $block_html .= '<div class="social-media-link instagram-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.001 9C10.3436 9 9.00098 10.3431 9.00098 12C9.00098 13.6573 10.3441 15 12.001 15C13.6583 15 15.001 13.6569 15.001 12C15.001 10.3427 13.6579 9 12.001 9ZM12.001 7C14.7614 7 17.001 9.2371 17.001 12C17.001 14.7605 14.7639 17 12.001 17C9.24051 17 7.00098 14.7629 7.00098 12C7.00098 9.23953 9.23808 7 12.001 7ZM18.501 6.74915C18.501 7.43926 17.9402 7.99917 17.251 7.99917C16.5609 7.99917 16.001 7.4384 16.001 6.74915C16.001 6.0599 16.5617 5.5 17.251 5.5C17.9393 5.49913 18.501 6.0599 18.501 6.74915ZM12.001 4C9.5265 4 9.12318 4.00655 7.97227 4.0578C7.18815 4.09461 6.66253 4.20007 6.17416 4.38967C5.74016 4.55799 5.42709 4.75898 5.09352 5.09255C4.75867 5.4274 4.55804 5.73963 4.3904 6.17383C4.20036 6.66332 4.09493 7.18811 4.05878 7.97115C4.00703 9.0752 4.00098 9.46105 4.00098 12C4.00098 14.4745 4.00753 14.8778 4.05877 16.0286C4.0956 16.8124 4.2012 17.3388 4.39034 17.826C4.5591 18.2606 4.7605 18.5744 5.09246 18.9064C5.42863 19.2421 5.74179 19.4434 6.17187 19.6094C6.66619 19.8005 7.19148 19.9061 7.97212 19.9422C9.07618 19.9939 9.46203 20 12.001 20C14.4755 20 14.8788 19.9934 16.0296 19.9422C16.8117 19.9055 17.3385 19.7996 17.827 19.6106C18.2604 19.4423 18.5752 19.2402 18.9074 18.9085C19.2436 18.5718 19.4445 18.2594 19.6107 17.8283C19.8013 17.3358 19.9071 16.8098 19.9432 16.0289C19.9949 14.9248 20.001 14.5389 20.001 12C20.001 9.52552 19.9944 9.12221 19.9432 7.97137C19.9064 7.18906 19.8005 6.66149 19.6113 6.17318C19.4434 5.74038 19.2417 5.42635 18.9084 5.09255C18.573 4.75715 18.2616 4.55693 17.8271 4.38942C17.338 4.19954 16.8124 4.09396 16.0298 4.05781C14.9258 4.00605 14.5399 4 12.001 4ZM12.001 2C14.7176 2 15.0568 2.01 16.1235 2.06C17.1876 2.10917 17.9135 2.2775 18.551 2.525C19.2101 2.77917 19.7668 3.1225 20.3226 3.67833C20.8776 4.23417 21.221 4.7925 21.476 5.45C21.7226 6.08667 21.891 6.81333 21.941 7.8775C21.9885 8.94417 22.001 9.28333 22.001 12C22.001 14.7167 21.991 15.0558 21.941 16.1225C21.8918 17.1867 21.7226 17.9125 21.476 18.55C21.2218 19.2092 20.8776 19.7658 20.3226 20.3217C19.7668 20.8767 19.2076 21.22 18.551 21.475C17.9135 21.7217 17.1876 21.89 16.1235 21.94C15.0568 21.9875 14.7176 22 12.001 22C9.28431 22 8.94514 21.99 7.87848 21.94C6.81431 21.8908 6.08931 21.7217 5.45098 21.475C4.79264 21.2208 4.23514 20.8767 3.67931 20.3217C3.12348 19.7658 2.78098 19.2067 2.52598 18.55C2.27848 17.9125 2.11098 17.1867 2.06098 16.1225C2.01348 15.0558 2.00098 14.7167 2.00098 12C2.00098 9.28333 2.01098 8.94417 2.06098 7.8775C2.11014 6.8125 2.27848 6.0875 2.52598 5.45C2.78014 4.79167 3.12348 4.23417 3.67931 3.67833C4.23514 3.1225 4.79348 2.78 5.45098 2.525C6.08848 2.2775 6.81348 2.11 7.87848 2.06C8.94514 2.0125 9.28431 2 12.001 2Z"/></svg>';
            $block_html .= '<a href="' . esc_url($instagram_url) . '" target="_blank" rel="noopener">rozowe_studio</a>';
            $block_html .= '</div>';
        }
        
        // Facebook link
        if (function_exists('rozowe_studio_has_facebook') && rozowe_studio_has_facebook()) {
            $facebook_url = rozowe_studio_get_facebook_url();
            $block_html .= '<div class="social-media-link facebook-link"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M7 10V14H10V21H14V14H17L18 10H14V8C14 7.73478 14.1054 7.48043 14.2929 7.29289C14.4804 7.10536 14.7348 7 15 7H18V3H15C13.6739 3 12.4021 3.52678 11.4645 4.46447C10.5268 5.40215 10 6.67392 10 8V10H7Z" stroke="#221516" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            $block_html .= '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener">rozowe_studio</a>';
            $block_html .= '</div>';
        }
        
        $block_html .= '</div>'; // Close photography-teaser-social-media
    }
    
    $block_html .= '</div>'; // Close photography-teaser-content-wrapper
    $block_html .= '</div>'; // Close photography-teaser-content-column
    $block_html .= '</div>'; // Close grid
    $block_html .= '</div>'; // Close container
    $block_html .= '</div>'; // Close photography-teaser-block

    return $block_html;
}