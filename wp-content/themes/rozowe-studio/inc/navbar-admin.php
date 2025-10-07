<?php
/**
 * Custom Navbar Admin Panel
 *
 * @package Różowe_Studio
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom navbar admin menu
 */
function rozowe_studio_add_navbar_admin_menu() {
    add_menu_page(
        'Navbar Settings',
        'Navbar',
        'manage_options',
        'navbar-settings',
        'rozowe_studio_navbar_admin_page',
        'dashicons-menu',
        30
    );
}
add_action('admin_menu', 'rozowe_studio_add_navbar_admin_menu');

/**
 * Enqueue admin scripts for navbar page
 */
function rozowe_studio_navbar_admin_scripts($hook) {
    // Only load on our admin page
    if ($hook != 'toplevel_page_navbar-settings') {
        return;
    }
    
    // Enqueue WordPress media scripts
    wp_enqueue_media();
    wp_enqueue_script('jquery');
}
add_action('admin_enqueue_scripts', 'rozowe_studio_navbar_admin_scripts');

/**
 * Navbar admin page callback
 */
function rozowe_studio_navbar_admin_page() {
    // Save settings if form is submitted
    if (isset($_POST['submit']) && wp_verify_nonce($_POST['navbar_nonce'], 'navbar_settings')) {
        // Save menu items
        $menu_items = array();
        for ($i = 1; $i <= 4; $i++) {
            if (!empty($_POST["menu_item_{$i}_title"])) {
                // Check if page is selected or custom URL is provided
                $url = '';
                if (!empty($_POST["menu_item_{$i}_page"])) {
                    $url = esc_url_raw($_POST["menu_item_{$i}_page"]);
                } elseif (!empty($_POST["menu_item_{$i}_url"])) {
                    $url = esc_url_raw($_POST["menu_item_{$i}_url"]);
                }
                
                if (!empty($url)) {
                    $menu_items[] = array(
                        'title' => sanitize_text_field($_POST["menu_item_{$i}_title"]),
                        'url' => $url,
                        'position' => $i
                    );
                }
            }
        }
        update_option('rozowe_studio_navbar_menu_items', $menu_items);
        
        // Save logo settings
        if (isset($_POST['navbar_logo_id'])) {
            update_option('rozowe_studio_navbar_logo_id', intval($_POST['navbar_logo_id']));
        }
        
        echo '<div class="notice notice-success"><p>Ustawienia zostały zapisane!</p></div>';
    }
    
    // Get current settings
    $menu_items = get_option('rozowe_studio_navbar_menu_items', array());
    $logo_id = get_option('rozowe_studio_navbar_logo_id', '');
    
    ?>
    <div class="wrap">
        <h1>Ustawienia Navbar</h1>
        <p>Zarządzaj pozycjami menu i logo w navbarze. Maksymalnie 4 pozycje menu.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('navbar_settings', 'navbar_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">Logo Navbar</th>
                    <td>
                        <div class="navbar-logo-upload">
                            <?php
                            $logo_url = '';
                            if ($logo_id) {
                                $logo_url = wp_get_attachment_url($logo_id);
                            }
                            ?>
                            <div class="navbar-logo-preview" style="margin-bottom: 10px;">
                                <?php if ($logo_url): ?>
                                    <img src="<?php echo esc_url($logo_url); ?>" style="max-width: 200px; max-height: 100px;" />
                                <?php else: ?>
                                    <p>Brak logo</p>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="navbar_logo_id" id="navbar_logo_id" value="<?php echo esc_attr($logo_id); ?>" />
                            <button type="button" class="button" id="navbar_logo_upload">Wybierz Logo</button>
                            <button type="button" class="button" id="navbar_logo_remove" style="<?php echo $logo_id ? '' : 'display:none;'; ?>">Usuń Logo</button>
                        </div>
                    </td>
                </tr>
            </table>
            
            <h2>Pozycje Menu</h2>
            <p>Dodaj maksymalnie 4 pozycje menu. Pozycje 1-2 będą wyświetlane po lewej stronie, pozycje 3-4 po prawej stronie.</p>
            
            <table class="form-table">
                <?php 
                // Get all pages for dropdown
                $pages = get_pages(array(
                    'sort_column' => 'post_title',
                    'sort_order' => 'ASC'
                ));
                ?>
                <?php for ($i = 1; $i <= 4; $i++): 
                    $current_item = null;
                    foreach ($menu_items as $item) {
                        if ($item['position'] == $i) {
                            $current_item = $item;
                            break;
                        }
                    }
                ?>
                <tr>
                    <th scope="row">Pozycja <?php echo $i; ?></th>
                    <td>
                        <fieldset>
                            <label for="menu_item_<?php echo $i; ?>_title">Tytuł:</label><br>
                            <input type="text" 
                                   name="menu_item_<?php echo $i; ?>_title" 
                                   id="menu_item_<?php echo $i; ?>_title" 
                                   value="<?php echo esc_attr($current_item ? $current_item['title'] : ''); ?>" 
                                   class="regular-text" 
                                   placeholder="Nazwa pozycji menu" />
                            <br><br>
                            
                            <label for="menu_item_<?php echo $i; ?>_page">Wybierz stronę:</label><br>
                            <select name="menu_item_<?php echo $i; ?>_page" 
                                    id="menu_item_<?php echo $i; ?>_page" 
                                    class="regular-text navbar-page-selector">
                                <option value="">-- Wybierz stronę --</option>
                                <option value="<?php echo esc_url(home_url('/')); ?>" 
                                        <?php echo ($current_item && $current_item['url'] == home_url('/')) ? 'selected' : ''; ?>>
                                    Strona główna
                                </option>
                                <?php foreach ($pages as $page): ?>
                                    <option value="<?php echo esc_url(get_permalink($page->ID)); ?>" 
                                            <?php echo ($current_item && $current_item['url'] == get_permalink($page->ID)) ? 'selected' : ''; ?>>
                                        <?php echo esc_html($page->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <br><br>
                            
                            <label for="menu_item_<?php echo $i; ?>_url">Lub wpisz własny URL:</label><br>
                            <input type="url" 
                                   name="menu_item_<?php echo $i; ?>_url" 
                                   id="menu_item_<?php echo $i; ?>_url" 
                                   value="<?php echo esc_attr($current_item ? $current_item['url'] : ''); ?>" 
                                   class="regular-text navbar-url-input" 
                                   placeholder="https://example.com" />
                            <p class="description">Możesz wybrać stronę z listy powyżej lub wpisać własny URL.</p>
                        </fieldset>
                    </td>
                </tr>
                <?php endfor; ?>
            </table>
            
            <?php submit_button('Zapisz Ustawienia'); ?>
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Logo upload
        var logoUploader;
        
        $('#navbar_logo_upload').click(function(e) {
            e.preventDefault();
            
            // Check if wp.media is available
            if (typeof wp === 'undefined' || !wp.media) {
                alert('WordPress Media Library nie jest dostępna. Odśwież stronę i spróbuj ponownie.');
                return;
            }
            
            if (logoUploader) {
                logoUploader.open();
                return;
            }
            
            logoUploader = wp.media({
                title: 'Wybierz Logo',
                button: {
                    text: 'Użyj tego logo'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            logoUploader.on('select', function() {
                var attachment = logoUploader.state().get('selection').first().toJSON();
                $('#navbar_logo_id').val(attachment.id);
                $('.navbar-logo-preview').html('<img src="' + attachment.url + '" style="max-width: 200px; max-height: 100px;" />');
                $('#navbar_logo_remove').show();
            });
            
            logoUploader.open();
        });
        
        $('#navbar_logo_remove').click(function(e) {
            e.preventDefault();
            $('#navbar_logo_id').val('');
            $('.navbar-logo-preview').html('<p>Brak logo</p>');
            $(this).hide();
        });
        
        // Page selector functionality
        $('.navbar-page-selector').change(function() {
            var selectedUrl = $(this).val();
            var urlInput = $(this).closest('fieldset').find('.navbar-url-input');
            
            if (selectedUrl) {
                urlInput.val(selectedUrl);
            }
        });
        
        // URL input change - clear page selector if custom URL is entered
        $('.navbar-url-input').on('input', function() {
            var pageSelector = $(this).closest('fieldset').find('.navbar-page-selector');
            var currentValue = $(this).val();
            var selectedPageValue = pageSelector.val();
            
            // If user is typing a custom URL that doesn't match selected page, clear page selector
            if (currentValue && currentValue !== selectedPageValue) {
                pageSelector.val('');
            }
        });
    });
    </script>
    <?php
}

/**
 * Get navbar menu items
 */
function rozowe_studio_get_navbar_menu_items() {
    return get_option('rozowe_studio_navbar_menu_items', array());
}

/**
 * Get navbar logo
 */
function rozowe_studio_get_navbar_logo() {
    $logo_id = get_option('rozowe_studio_navbar_logo_id', '');
    if ($logo_id) {
        return array(
            'id' => $logo_id,
            'url' => wp_get_attachment_url($logo_id),
            'alt' => get_post_meta($logo_id, '_wp_attachment_image_alt', true)
        );
    }
    return false;
}

/**
 * Display custom navbar
 */
function rozowe_studio_display_navbar() {
    $menu_items = rozowe_studio_get_navbar_menu_items();
    $logo = rozowe_studio_get_navbar_logo();
    
    // Split menu items into left and right
    $left_items = array();
    $right_items = array();
    
    foreach ($menu_items as $item) {
        if ($item['position'] <= 2) {
            $left_items[] = $item;
        } else {
            $right_items[] = $item;
        }
    }
    
    ?>
    <nav class="custom-navbar">
        <div class="container">
            <div class="navbar-content">
                <!-- Left menu items -->
                <div class="navbar-left">
                    <?php foreach ($left_items as $item): ?>
                        <a href="<?php echo esc_url($item['url']); ?>" class="navbar-item">
                            <?php echo esc_html($item['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <!-- Logo in center -->
                <div class="navbar-center">
                    <?php if ($logo): ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-logo">
                            <img src="<?php echo esc_url($logo['url']); ?>" 
                                 alt="<?php echo esc_attr($logo['alt'] ? $logo['alt'] : get_bloginfo('name')); ?>" />
                        </a>
                    <?php else: ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-logo navbar-logo-text">
                            <?php bloginfo('name'); ?>
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Right menu items -->
                <div class="navbar-right">
                    <?php foreach ($right_items as $item): ?>
                        <a href="<?php echo esc_url($item['url']); ?>" class="navbar-item">
                            <?php echo esc_html($item['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </nav>
    <?php
}
