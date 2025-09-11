<?php
/**
 * Contact Form Block
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
function rozowe_studio_contact_form_block_assets() {
    // Editor script
    wp_register_script(
        'rozowe-studio-contact-form-editor',
        get_template_directory_uri() . '/blocks/contact-form/contact-form-editor.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
        '1.0.0',
        true
    );

    // Editor style
    wp_register_style(
        'rozowe-studio-contact-form-editor',
        get_template_directory_uri() . '/blocks/contact-form/contact-form-editor.css',
        array('wp-edit-blocks'),
        '1.0.0'
    );

    // Frontend style
    wp_register_style(
        'rozowe-studio-contact-form',
        get_template_directory_uri() . '/blocks/contact-form/contact-form.css',
        array(),
        '1.0.0'
    );
}
add_action('enqueue_block_editor_assets', 'rozowe_studio_contact_form_block_assets');

/**
 * Register Contact Form Block
 */
function rozowe_studio_register_contact_form_block() {
    // Check if Gutenberg is available
    if (!function_exists('register_block_type')) {
        return;
    }

    // Register the block
    register_block_type('rozowe-studio/contact-form', array(
        'editor_script' => 'rozowe-studio-contact-form-editor',
        'editor_style'  => 'rozowe-studio-contact-form-editor',
        'style'         => 'rozowe-studio-contact-form',
        'render_callback' => 'rozowe_studio_render_contact_form_block',
        'attributes' => array(
            'shortcode' => array(
                'type' => 'string',
                'default' => '',
            ),
            'title' => array(
                'type' => 'string',
                'default' => '',
            ),
        ),
    ));
}
add_action('init', 'rozowe_studio_register_contact_form_block');

/**
 * Render Contact Form Block
 */
function rozowe_studio_render_contact_form_block($attributes) {
    $shortcode = $attributes['shortcode'] ?? '';
    $title = $attributes['title'] ?? '';

    // Check if Contact Form 7 is active
    if (!function_exists('wpcf7_contact_form')) {
        return '<div class="contact-form-block-error">Contact Form 7 plugin is not active.</div>';
    }

    // Check if shortcode is provided
    if (empty($shortcode)) {
        return '<div class="contact-form-block-error">Please provide a Contact Form 7 shortcode.</div>';
    }

    // Validate shortcode format
    if (!preg_match('/\[contact-form-7[^\]]*\]/', $shortcode)) {
        return '<div class="contact-form-block-error">Invalid Contact Form 7 shortcode format.</div>';
    }

    ob_start();
    ?>
    <div class="contact-form-block">
        <?php if ($title): ?>
            <h2 class="contact-form-title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        
        <div class="contact-form-wrapper">
            <?php echo do_shortcode($shortcode); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
