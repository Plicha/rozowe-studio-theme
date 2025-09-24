<?php
/**
 * Theme setup and configuration
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
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    
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
 * Add SVG support to WordPress
 */
function rozowe_studio_add_svg_support($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'rozowe_studio_add_svg_support');

/**
 * Fix SVG display in admin
 */
function rozowe_studio_fix_svg_display($response, $attachment, $meta) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
        $response['image'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150,
        );
        $response['thumb'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150,
        );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'rozowe_studio_fix_svg_display', 10, 3);

/**
 * Register image sizes
 */
function rozowe_studio_image_sizes() {
    add_image_size('hero-image', 1920, 1080, true);
    add_image_size('thumbnail-large', 400, 300, true);
}
add_action('after_setup_theme', 'rozowe_studio_image_sizes');

/**
 * Change post type labels to "Reportaże Ślubne"
 */
function rozowe_studio_change_post_labels($labels) {
    $labels->name = 'Reportaże Ślubne';
    $labels->singular_name = 'Reportaż Ślubny';
    $labels->add_new = 'Dodaj nowy';
    $labels->add_new_item = 'Dodaj nowy reportaż ślubny';
    $labels->edit_item = 'Edytuj reportaż ślubny';
    $labels->new_item = 'Nowy reportaż ślubny';
    $labels->view_item = 'Zobacz reportaż ślubny';
    $labels->search_items = 'Szukaj reportaży ślubnych';
    $labels->not_found = 'Nie znaleziono reportaży ślubnych';
    $labels->not_found_in_trash = 'Nie znaleziono reportaży ślubnych w koszu';
    $labels->menu_name = 'Reportaże Ślubne';
    $labels->name_admin_bar = 'Reportaż Ślubny';
    return $labels;
}
add_filter('post_type_labels_post', 'rozowe_studio_change_post_labels');

/**
 * Add custom admin menu icon for posts
 */
function rozowe_studio_admin_menu_icon() {
    echo '<style>
        #menu-posts .wp-menu-image:before {
            content: "\f306" !important;
            font-family: dashicons !important;
        }
        #menu-posts:hover .wp-menu-image:before,
        #menu-posts.current .wp-menu-image:before {
            content: "\f306" !important;
        }
    </style>';
}
add_action('admin_head', 'rozowe_studio_admin_menu_icon');

/**
 * Remove Categories and Tags from admin menu
 */
function rozowe_studio_remove_taxonomies_from_menu() {
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
}
add_action('admin_menu', 'rozowe_studio_remove_taxonomies_from_menu');

/**
 * Remove Categories and Tags columns from posts table
 */
function rozowe_studio_remove_taxonomy_columns($columns) {
    unset($columns['categories']);
    unset($columns['tags']);
    unset($columns['comments']);
    return $columns;
}
add_filter('manage_posts_columns', 'rozowe_studio_remove_taxonomy_columns');

/**
 * Remove Comments from admin menu
 */
function rozowe_studio_remove_comments_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'rozowe_studio_remove_comments_menu');

/**
 * Disable comments support for posts
 */
function rozowe_studio_disable_comments() {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('post', 'trackbacks');
}
add_action('init', 'rozowe_studio_disable_comments');

/**
 * Add custom post type archive support
 */
function rozowe_studio_add_archive_support() {
    // Ensure post archives are properly supported
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'rozowe_studio_add_archive_support');

/**
 * Force posts to use archive.php template
 */
function rozowe_studio_force_posts_archive_template($template) {
    // Check if this is the posts page (blog listing)
    if (is_home() || (is_page() && get_option('page_for_posts') == get_the_ID())) {
        $archive_template = locate_template('archive.php');
        if ($archive_template) {
            return $archive_template;
        }
    }
    return $template;
}
add_filter('template_include', 'rozowe_studio_force_posts_archive_template');

/**
 * Add debug info to check which template is being used
 */
function rozowe_studio_debug_template($template) {
    if (current_user_can('manage_options') && isset($_GET['debug_template'])) {
        echo '<!-- Template being used: ' . basename($template) . ' -->';
    }
    return $template;
}
add_filter('template_include', 'rozowe_studio_debug_template', 999);

/**
 * Helper function to get photography page URL safely
 */
function rozowe_studio_get_photography_page_url() {
    // First try by slug
    $photography_page = get_page_by_path('photography');
    if ($photography_page) {
        return get_permalink($photography_page->ID);
    }
    
    // Try by template
    $photography_pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-photography.php'
    ));
    if (!empty($photography_pages)) {
        return get_permalink($photography_pages[0]->ID);
    }
    
    // Try by title
    $photography_pages = get_pages(array(
        'post_title' => 'Fotografia'
    ));
    if (!empty($photography_pages)) {
        return get_permalink($photography_pages[0]->ID);
    }
    
    // Fallback to home
    return home_url();
}

/**
 * Helper function to get photography page title safely
 */
function rozowe_studio_get_photography_page_title() {
    // First try by slug
    $photography_page = get_page_by_path('photography');
    if ($photography_page) {
        return get_the_title($photography_page->ID);
    }
    
    // Try by template
    $photography_pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-photography.php'
    ));
    if (!empty($photography_pages)) {
        return get_the_title($photography_pages[0]->ID);
    }
    
    // Try by title
    $photography_pages = get_pages(array(
        'post_title' => 'Fotografia'
    ));
    if (!empty($photography_pages)) {
        return get_the_title($photography_pages[0]->ID);
    }
    
    // Fallback to default
    return 'Fotografia';
}

/**
 * Helper function to get posts page title safely
 */
function rozowe_studio_get_posts_page_title() {
    $posts_page_id = get_option('page_for_posts');
    if ($posts_page_id) {
        return get_the_title($posts_page_id);
    }
    
    // Fallback to default
    return 'Reportaże Ślubne';
}

/**
 * Add custom meta box for page description
 */
function rozowe_studio_add_page_description_meta_box() {
    add_meta_box(
        'page-description',
        'Page Description',
        'rozowe_studio_page_description_meta_box_callback',
        array('page', 'post'),
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'rozowe_studio_add_page_description_meta_box');

/**
 * Meta box callback function
 */
function rozowe_studio_page_description_meta_box_callback($post) {
    wp_nonce_field('rozowe_studio_page_description_nonce', 'page_description_nonce');
    
    $description = get_post_meta($post->ID, '_page_description', true);
    
    echo '<label for="page_description">Page description (displayed under title):</label>';
    echo '<textarea id="page_description" name="page_description" rows="3" style="width: 100%; margin-top: 5px;">' . esc_textarea($description) . '</textarea>';
    echo '<p class="description">This description will be displayed under the page title.</p>';
}

/**
 * Save page description meta data
 */
function rozowe_studio_save_page_description($post_id) {
    if (!isset($_POST['page_description_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['page_description_nonce'], 'rozowe_studio_page_description_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['page_description'])) {
        update_post_meta($post_id, '_page_description', sanitize_textarea_field($_POST['page_description']));
    }
}
add_action('save_post', 'rozowe_studio_save_page_description');

/**
 * Helper function to get page description
 */
function rozowe_studio_get_page_description($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $description = get_post_meta($post_id, '_page_description', true);
    return $description;
}

/**
 * Helper function to get post description (for individual posts)
 */
function rozowe_studio_get_post_description($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $description = get_post_meta($post_id, '_page_description', true);
    return $description;
}
