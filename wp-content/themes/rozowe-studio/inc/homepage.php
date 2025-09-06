<?php
/**
 * Homepage setup and configuration
 *
 * @package Różowe_Studio
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Set up static front page
 */
function rozowe_studio_setup_front_page() {
    // Check if front page is already set
    if (get_option('show_on_front') !== 'page') {
        // Create home page if it doesn't exist using WP_Query instead of deprecated get_page_by_title
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'title' => 'Strona główna',
            'posts_per_page' => 1
        );
        
        $home_page_query = new WP_Query($args);
        
        if (!$home_page_query->have_posts()) {
            $home_page_id = wp_insert_post(array(
                'post_title'    => 'Strona główna',
                'post_content'  => '<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center">Witamy w Różowym Studio</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Tworzymy piękne i funkcjonalne strony internetowe</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"primary","textColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background wp-element-button" href="#kontakt">Skontaktuj się z nami</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_name'     => 'strona-glowna'
            ));
        } else {
            $home_page_id = $home_page_query->posts[0]->ID;
        }
        
        // Set front page
        update_option('show_on_front', 'page');
        update_option('page_on_front', $home_page_id);
    }
}
add_action('after_switch_theme', 'rozowe_studio_setup_front_page');

/**
 * Run setup on theme activation
 */
function rozowe_studio_theme_activation() {
    rozowe_studio_setup_front_page();
}
add_action('after_switch_theme', 'rozowe_studio_theme_activation');
