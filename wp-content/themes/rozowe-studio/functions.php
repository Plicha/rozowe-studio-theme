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
 * Load theme files
 */
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/widgets.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/navbar-admin.php';
require_once get_template_directory() . '/inc/security.php';
require_once get_template_directory() . '/inc/homepage.php';
require_once get_template_directory() . '/inc/helpers.php';

/**
 * Load custom blocks
 */
require_once get_template_directory() . '/blocks/main-banner/main-banner.php';
require_once get_template_directory() . '/blocks/story-frames/story-frames.php';
require_once get_template_directory() . '/blocks/content-letter/content-letter.php';
require_once get_template_directory() . '/blocks/contact-form/contact-form.php';
require_once get_template_directory() . '/blocks/photography-teaser/photography-teaser.php';
require_once get_template_directory() . '/blocks/photo-gallery/photo-gallery.php';