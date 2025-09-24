<?php
/**
 * Template Name: Jewelry
 * The template for displaying the Jewelry page
 *
 * @package Różowe_Studio
 */

get_header(); ?>

<main id="main" class="site-main jewelry-page bg-white-200">
    <div class="container">
        <div class="page-content">
            <?php
            while (have_posts()) :
                the_post();
                ?>

                <div class="jewelry-content">
                    <?php
                    the_content();
                    ?>
                </div>

                <?php
                // Optional: Add jewelry-specific sections
                if (is_page('jewelry')) {
                    ?>
                    <section class="jewelry-gallery">
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
