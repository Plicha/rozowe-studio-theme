<?php
/**
 * Template Name: Page with Blocks Only
 * Template for displaying pages with Gutenberg blocks content only
 *
 * @package Różowe_Studio
 */

get_header(); ?>

<main id="main" class="site-main page-blocks archive-page">
    <div class="container">
        <nav class="breadcrumbs" aria-label="breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item">
                    <a href="<?php echo home_url(); ?>">Home</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12" fill="none">
                        <path d="M1.5 1L6.5 6L1.5 11" stroke="#9A9192" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php echo rozowe_studio_get_photography_page_url(); ?>">Fotografia</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12" fill="none">
                        <path d="M1.5 1L6.5 6L1.5 11" stroke="#9A9192" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php the_title(); ?>
                </li>
            </ol>
        </nav>

        <header class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <?php 
            $page_description = rozowe_studio_get_page_description(get_the_ID());
            
            if ($page_description) : ?>
                <p class="page-description"><?php echo esc_html($page_description); ?></p>
            <?php endif; ?>
        </header>

        <?php
        // Display Gutenberg blocks content
        if (have_posts()) :
            while (have_posts()) : the_post();
                echo '<div class="page-content">';
                echo apply_filters('the_content', get_the_content());
                echo '</div>';
            endwhile;
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>
