<?php
/**
 * The template for displaying single posts (individual wedding reports)
 *
 * @package Różowe_Studio
 */

get_header(); ?>

<main id="main" class="site-main archive-page single-post">
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
                <li class="breadcrumb-item">
                    <a href="<?php echo get_post_type_archive_link('post'); ?>">
                        <?php echo rozowe_studio_get_posts_page_title(); ?>
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12" fill="none">
                        <path d="M1.5 1L6.5 6L1.5 11" stroke="#9A9192" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php the_title(); ?>
                </li>
            </ol>
        </nav>

        

        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php
                    // Display post description from the current post
                    $post_description = rozowe_studio_get_post_description();
                    
                    if ($post_description) : ?>
                        <p class="page-description"><?php echo esc_html($post_description); ?></p>
                    <?php endif; ?>
                </header>   

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    
                    <div class="back-to-archive">
                        <a href="<?php echo get_post_type_archive_link('post'); ?>" class="btn btn-secondary">
                            Wróć do wszystkich reportaży
                        </a>
                    </div>
                </footer>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
