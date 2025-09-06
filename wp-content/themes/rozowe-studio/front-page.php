<?php
/**
 * The front page template file
 *
 * @package Różowe_Studio
 */

get_header(); ?>

<main id="main" class="site-main front-page">
    <div class="container">
        <?php
        // Sprawdź czy strona główna jest ustawiona jako strona statyczna
        if (get_option('show_on_front') === 'page' && get_option('page_on_front')) {
            $front_page_id = get_option('page_on_front');
            $front_page = get_post($front_page_id);
            
            if ($front_page) {
                // Wyświetl tytuł strony
                echo '<h1 class="page-title">' . get_the_title($front_page_id) . '</h1>';
                
                // Wyświetl treść strony (bloki Gutenberg)
                echo '<div class="page-content">';
                echo apply_filters('the_content', $front_page->post_content);
                echo '</div>';
            }
        } else {
            // Fallback - wyświetl domyślną treść
            echo '<h1 class="page-title">Witamy w Różowym Studio</h1>';
            echo '<div class="page-content">';
            echo '<p>Tworzymy piękne i funkcjonalne strony internetowe</p>';
            echo '</div>';
        }
        ?>
    </div>
</main>

<?php get_footer(); ?>
