<?php
/**
 * Story Frames Block
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
function rozowe_studio_story_frames_block_assets() {
    // Editor script
    wp_register_script(
        'rozowe-studio-story-frames-editor',
        get_template_directory_uri() . '/blocks/story-frames/story-frames-editor.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-block-editor'),
        '1.0.0',
        true
    );

    // Editor style
    wp_register_style(
        'rozowe-studio-story-frames-editor',
        get_template_directory_uri() . '/blocks/story-frames/story-frames-editor.css',
        array('wp-edit-blocks'),
        '1.0.0'
    );

    // Frontend style
    wp_register_style(
        'rozowe-studio-story-frames',
        get_template_directory_uri() . '/blocks/story-frames/story-frames.css',
        array(),
        '1.0.0'
    );
}
add_action('enqueue_block_editor_assets', 'rozowe_studio_story_frames_block_assets');

/**
 * Register Story Frames Block
 */
function rozowe_studio_register_story_frames_block() {
    // Check if Gutenberg is available
    if (!function_exists('register_block_type')) {
        return;
    }

    // Debug: Log block registration
    error_log('Registering Story Frames block...');

    // Register the block
    $result = register_block_type('rozowe-studio/story-frames', array(
        'editor_script' => 'rozowe-studio-story-frames-editor',
        'editor_style'  => 'rozowe-studio-story-frames-editor',
        'style'         => 'rozowe-studio-story-frames',
        'render_callback' => 'rozowe_studio_render_story_frames_block',
        'attributes' => array(
            'image' => array(
                'type' => 'object',
                'default' => null,
            ),
            'imageAlt' => array(
                'type' => 'string',
                'default' => '',
            ),
        ),
    ));

    // Debug: Log registration result
    if ($result) {
        error_log('Story Frames block registered successfully');
    } else {
        error_log('Failed to register Story Frames block');
    }
}
add_action('init', 'rozowe_studio_register_story_frames_block');

/**
 * Render Story Frames Block (for image handling)
 */
function rozowe_studio_render_story_frames_block($attributes, $content) {
    $image = $attributes['image'] ?? null;
    $image_alt = $attributes['imageAlt'] ?? '';

    $image_url = $image ? $image['url'] : '';

    // If we have an image, we need to inject it into the content
    if ($image_url) {
        $image_html = '<div class="story-frames-image"><img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" /></div>';
        
        // Find the third grid-col-6 and add the image
        $parts = explode('<div class="grid-col-6">', $content, 2);
        if (count($parts) >= 2) {
            $content = $parts[0] . '<div class="grid-col-6">' . $image_html . $parts[1];
        }
    }

    return $content;
}
