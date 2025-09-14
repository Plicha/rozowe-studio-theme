<?php
/**
 * Template Name: Photography
 * The template for displaying the Photography page
 *
 * @package Różowe_Studio
 */

get_header(); ?>

<main id="main" class="site-main photography-page">
    <div class="container">
        <div class="page-content">
            <?php
            while (have_posts()) :
                the_post();
                ?>

                <div class="photography-content">
                    <?php
                    the_content();
                    ?>
                </div>

                <?php
                // Optional: Add photography-specific sections
                if (is_page('photography')) {
                    ?>
                    <section class="photography-gallery">
                        <?php
                        ?>
                    </section>
                    <?php
                }
                ?>

            <?php endwhile; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
