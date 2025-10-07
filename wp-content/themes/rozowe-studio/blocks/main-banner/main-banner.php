<?php
/**
 * Main Banner Block
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
function rozowe_studio_main_banner_block_assets() {
    // Editor script
    wp_register_script(
        'rozowe-studio-main-banner-editor',
        get_template_directory_uri() . '/blocks/main-banner/main-banner-editor.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
        '1.0.0',
        true
    );

    // Editor style
    wp_register_style(
        'rozowe-studio-main-banner-editor',
        get_template_directory_uri() . '/blocks/main-banner/main-banner-editor.css',
        array('wp-edit-blocks'),
        '1.0.0'
    );

    // Frontend style
    wp_register_style(
        'rozowe-studio-main-banner',
        get_template_directory_uri() . '/blocks/main-banner/main-banner.css',
        array(),
        '1.0.0'
    );
}
add_action('enqueue_block_editor_assets', 'rozowe_studio_main_banner_block_assets');

/**
 * Register Main Banner Block
 */
function rozowe_studio_register_main_banner_block() {
    // Check if Gutenberg is available
    if (!function_exists('register_block_type')) {
        return;
    }

    // Register the block
    register_block_type('rozowe-studio/main-banner', array(
        'editor_script' => 'rozowe-studio-main-banner-editor',
        'editor_style'  => 'rozowe-studio-main-banner-editor',
        'style'         => 'rozowe-studio-main-banner',
        'render_callback' => 'rozowe_studio_render_main_banner_block',
        'attributes' => array(
            'blockId' => array(
                'type' => 'string',
                'default' => '',
            ),
            'backgroundImage' => array(
                'type' => 'object',
                'default' => null,
            ),
            'centerImage' => array(
                'type' => 'object',
                'default' => null,
            ),
            'centerImageAlt' => array(
                'type' => 'string',
                'default' => '',
            ),
        ),
    ));
}
add_action('init', 'rozowe_studio_register_main_banner_block');

/**
 * Render Main Banner Block
 */
function rozowe_studio_render_main_banner_block($attributes) {
    $block_id = $attributes['blockId'] ?? '';
    $background_image = $attributes['backgroundImage'] ?? null;
    $center_image = $attributes['centerImage'] ?? null;
    $center_image_alt = $attributes['centerImageAlt'] ?? '';

    // Get image URLs
    $background_url = $background_image ? $background_image['url'] : '';
    $center_url = $center_image ? $center_image['url'] : '';

    // Check if center image is SVG
    $is_svg = $center_url && pathinfo($center_url, PATHINFO_EXTENSION) === 'svg';

    ob_start();
    ?>
    <div class="main-banner-block"<?php echo $background_url ? ' style="background-image: url(\'' . esc_url($background_url) . '\');"' : ''; ?><?php echo $block_id ? ' id="' . esc_attr($block_id) . '"' : ''; ?>>
        <?php if ($center_url): ?>
            <div class="main-banner-content">
                <?php if ($is_svg): ?>
                    <?php
                    // Get SVG content using local file path instead of URL
                    $upload_dir = wp_upload_dir();
                    $svg_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $center_url);
                    
                    if (file_exists($svg_path)) {
                        $svg_content = file_get_contents($svg_path);
                        if ($svg_content) {
                            echo $svg_content;
                        }
                    } else {
                        // Fallback to img tag if file doesn't exist
                        echo '<img src="' . esc_url($center_url) . '" alt="' . esc_attr($center_image_alt) . '" class="main-banner-center-image" />';
                    }
                    ?>
                <?php else: ?>
                    <img src="<?php echo esc_url($center_url); ?>" 
                         alt="<?php echo esc_attr($center_image_alt); ?>" 
                         class="main-banner-center-image" />
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
} 