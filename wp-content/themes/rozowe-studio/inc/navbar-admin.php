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
        
        // Save white logo settings for footer
        if (isset($_POST['navbar_logo_white_id'])) {
            update_option('rozowe_studio_navbar_logo_white_id', intval($_POST['navbar_logo_white_id']));
        }
        
        echo '<div class="notice notice-success"><p>Ustawienia zostały zapisane!</p></div>';
    }
    
    // Get current settings
    $menu_items = get_option('rozowe_studio_navbar_menu_items', array());
    $logo_id = get_option('rozowe_studio_navbar_logo_id', '');
    $logo_white_id = get_option('rozowe_studio_navbar_logo_white_id', '');
    
    ?>
    <div class="wrap">
        <h1>Ustawienia Navbar</h1>
        <p>Zarządzaj pozycjami menu i logo w navbarze. Maksymalnie 4 pozycje menu.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('navbar_settings', 'navbar_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">Logo Navbar (Ciemne)</th>
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
                <tr>
                    <th scope="row">Logo Footer (Białe)</th>
                    <td>
                        <div class="navbar-logo-white-upload">
                            <?php
                            $logo_white_url = '';
                            if ($logo_white_id) {
                                $logo_white_url = wp_get_attachment_url($logo_white_id);
                            }
                            ?>
                            <div class="navbar-logo-white-preview" style="margin-bottom: 10px;">
                                <?php if ($logo_white_url): ?>
                                    <img src="<?php echo esc_url($logo_white_url); ?>" style="max-width: 200px; max-height: 100px;" />
                                <?php else: ?>
                                    <p>Brak białego logo</p>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="navbar_logo_white_id" id="navbar_logo_white_id" value="<?php echo esc_attr($logo_white_id); ?>" />
                            <button type="button" class="button" id="navbar_logo_white_upload">Wybierz Białe Logo</button>
                            <button type="button" class="button" id="navbar_logo_white_remove" style="<?php echo $logo_white_id ? '' : 'display:none;'; ?>">Usuń Logo</button>
                            <p class="description">Białe logo będzie używane w stopce na ciemnym tle.</p>
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
        var logoWhiteUploader;
        
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
        
        // White logo upload
        $('#navbar_logo_white_upload').click(function(e) {
            e.preventDefault();
            
            // Check if wp.media is available
            if (typeof wp === 'undefined' || !wp.media) {
                alert('WordPress Media Library nie jest dostępna. Odśwież stronę i spróbuj ponownie.');
                return;
            }
            
            if (logoWhiteUploader) {
                logoWhiteUploader.open();
                return;
            }
            
            logoWhiteUploader = wp.media({
                title: 'Wybierz Białe Logo',
                button: {
                    text: 'Użyj tego logo'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            logoWhiteUploader.on('select', function() {
                var attachment = logoWhiteUploader.state().get('selection').first().toJSON();
                $('#navbar_logo_white_id').val(attachment.id);
                $('.navbar-logo-white-preview').html('<img src="' + attachment.url + '" style="max-width: 200px; max-height: 100px;" />');
                $('#navbar_logo_white_remove').show();
            });
            
            logoWhiteUploader.open();
        });
        
        $('#navbar_logo_white_remove').click(function(e) {
            e.preventDefault();
            $('#navbar_logo_white_id').val('');
            $('.navbar-logo-white-preview').html('<p>Brak białego logo</p>');
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
 * Get navbar white logo (for footer)
 */
function rozowe_studio_get_navbar_logo_white() {
    $logo_id = get_option('rozowe_studio_navbar_logo_white_id', '');
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
                <!-- Mobile toggle button -->
                <button class="navbar-toggle" aria-label="Toggle mobile menu">
                    <span class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                
                <!-- Left menu items (desktop) -->
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
                
                <!-- Right menu items (desktop) -->
                <div class="navbar-right">
                    <?php foreach ($right_items as $item): ?>
                        <a href="<?php echo esc_url($item['url']); ?>" class="navbar-item">
                            <?php echo esc_html($item['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu overlay -->
        <div class="navbar-mobile-overlay"></div>
        <div class="navbar-mobile-menu">
            <div class="navbar-mobile-header">
                <div class="navbar-mobile-logo">
                    <?php if ($logo): ?>
                        <img src="<?php echo esc_url($logo['url']); ?>" 
                             alt="<?php echo esc_attr($logo['alt'] ? $logo['alt'] : get_bloginfo('name')); ?>" />
                    <?php else: ?>
                        <span class="navbar-logo-text"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="navbar-mobile-items">
                <?php foreach ($menu_items as $item): ?>
                    <a href="<?php echo esc_url($item['url']); ?>" class="navbar-item">
                        <?php echo esc_html($item['title']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>
    <?php
}

/**
 * Display custom footer
 */
function rozowe_studio_display_footer() {
    $menu_items = rozowe_studio_get_navbar_menu_items();
    $logo_white = rozowe_studio_get_navbar_logo_white();
    
    ?>
    <footer id="colophon" class="custom-footer">
        <div class="container">
            <div class="footer-content">
                <!-- Logo section -->
                <div class="footer-logo">
                    <?php if ($logo_white): ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo-link">
                            <img src="<?php echo esc_url($logo_white['url']); ?>" 
                                 alt="<?php echo esc_attr($logo_white['alt'] ? $logo_white['alt'] : get_bloginfo('name')); ?>" />
                        </a>
                    <?php else: ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo-link footer-logo-text">
                            <?php bloginfo('name'); ?>
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Navigation links -->
                <div class="footer-navigation">
                    <nav class="footer-nav">
                        <?php foreach ($menu_items as $item): ?>
                            <a href="<?php echo esc_url($item['url']); ?>" class="footer-nav-item">
                                <?php echo esc_html($item['title']); ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                </div>
                
                <!-- Social media links -->
                <div class="footer-social">
                    <?php
                    $has_social = false;
                    
                    // Instagram link
                    if (function_exists('rozowe_studio_has_instagram') && rozowe_studio_has_instagram()) {
                        $instagram_url = rozowe_studio_get_instagram_url();
                        $has_social = true;
                        ?>
                        <div class="footer-social-item">
                            <a href="<?php echo esc_url($instagram_url); ?>" target="_blank" rel="noopener" class="footer-social-link instagram-link">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.001 9C10.3436 9 9.00098 10.3431 9.00098 12C9.00098 13.6573 10.3441 15 12.001 15C13.6583 15 15.001 13.6569 15.001 12C15.001 10.3427 13.6579 9 12.001 9ZM12.001 7C14.7614 7 17.001 9.2371 17.001 12C17.001 14.7605 14.7639 17 12.001 17C9.24051 17 7.00098 14.7629 7.00098 12C7.00098 9.23953 9.23808 7 12.001 7ZM18.501 6.74915C18.501 7.43926 17.9402 7.99917 17.251 7.99917C16.5609 7.99917 16.001 7.4384 16.001 6.74915C16.001 6.0599 16.5617 5.5 17.251 5.5C17.9393 5.49913 18.501 6.0599 18.501 6.74915ZM12.001 4C9.5265 4 9.12318 4.00655 7.97227 4.0578C7.18815 4.09461 6.66253 4.20007 6.17416 4.38967C5.74016 4.55799 5.42709 4.75898 5.09352 5.09255C4.75867 5.4274 4.55804 5.73963 4.3904 6.17383C4.20036 6.66332 4.09493 7.18811 4.05878 7.97115C4.00703 9.0752 4.00098 9.46105 4.00098 12C4.00098 14.4745 4.00753 14.8778 4.05877 16.0286C4.0956 16.8124 4.2012 17.3388 4.39034 17.826C4.5591 18.2606 4.7605 18.5744 5.09246 18.9064C5.42863 19.2421 5.74179 19.4434 6.17187 19.6094C6.66619 19.8005 7.19148 19.9061 7.97212 19.9422C9.07618 19.9939 9.46203 20 12.001 20C14.4755 20 14.8788 19.9934 16.0296 19.9422C16.8117 19.9055 17.3385 19.7996 17.827 19.6106C18.2604 19.4423 18.5752 19.2402 18.9074 18.9085C19.2436 18.5718 19.4445 18.2594 19.6107 17.8283C19.8013 17.3358 19.9071 16.8098 19.9432 16.0289C19.9949 14.9248 20.001 14.5389 20.001 12C20.001 9.52552 19.9944 9.12221 19.9432 7.97137C19.9064 7.18906 19.8005 6.66149 19.6113 6.17318C19.4434 5.74038 19.2417 5.42635 18.9084 5.09255C18.573 4.75715 18.2616 4.55693 17.8271 4.38942C17.338 4.19954 16.8124 4.09396 16.0298 4.05781C14.9258 4.00605 14.5399 4 12.001 4ZM12.001 2C14.7176 2 15.0568 2.01 16.1235 2.06C17.1876 2.10917 17.9135 2.2775 18.551 2.525C19.2101 2.77917 19.7668 3.1225 20.3226 3.67833C20.8776 4.23417 21.221 4.7925 21.476 5.45C21.7226 6.08667 21.891 6.81333 21.941 7.8775C21.9885 8.94417 22.001 9.28333 22.001 12C22.001 14.7167 21.991 15.0558 21.941 16.1225C21.8918 17.1867 21.7226 17.9125 21.476 18.55C21.2218 19.2092 20.8776 19.7658 20.3226 20.3217C19.7668 20.8767 19.2076 21.22 18.551 21.475C17.9135 21.7217 17.1876 21.89 16.1235 21.94C15.0568 21.9875 14.7176 22 12.001 22C9.28431 22 8.94514 21.99 7.87848 21.94C6.81431 21.8908 6.08931 21.7217 5.45098 21.475C4.79264 21.2208 4.23514 20.8767 3.67931 20.3217C3.12348 19.7658 2.78098 19.2067 2.52598 18.55C2.27848 17.9125 2.11098 17.1867 2.06098 16.1225C2.01348 15.0558 2.00098 14.7167 2.00098 12C2.00098 9.28333 2.01098 8.94417 2.06098 7.8775C2.11014 6.8125 2.27848 6.0875 2.52598 5.45C2.78014 4.79167 3.12348 4.23417 3.67931 3.67833C4.23514 3.1225 4.79348 2.78 5.45098 2.525C6.08848 2.2775 6.81348 2.11 7.87848 2.06C8.94514 2.0125 9.28431 2 12.001 2Z"/>
                                </svg>
                            </a>
                        </div>
                        <?php
                    }
                    
                    // Facebook link
                    if (function_exists('rozowe_studio_has_facebook') && rozowe_studio_has_facebook()) {
                        $facebook_url = rozowe_studio_get_facebook_url();
                        $has_social = true;
                        ?>
                        <div class="footer-social-item">
                            <a href="<?php echo esc_url($facebook_url); ?>" target="_blank" rel="noopener" class="footer-social-link facebook-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M7 10V14H10V21H14V14H17L18 10H14V8C14 7.73478 14.1054 7.48043 14.2929 7.29289C14.4804 7.10536 14.7348 7 15 7H18V3H15C13.6739 3 12.4021 3.52678 11.4645 4.46447C10.5268 5.40215 10 6.67392 10 8V10H7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                        <?php
                    }
                    
                    if (!$has_social) {
                        ?>
                        <div class="footer-social-placeholder">
                            <p>Brak linków społecznościowych</p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </footer>
    <?php
}
