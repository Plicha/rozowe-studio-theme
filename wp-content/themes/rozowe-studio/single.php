<?php
/**
 * The template for displaying single posts (individual wedding reports)
 *
 * @package Różowe_Studio
 */

get_header(); ?>

<main id="main" class="site-main single-post">
    <div class="container">
        <nav class="breadcrumbs" aria-label="breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item">
                    <a href="<?php echo home_url(); ?>">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php echo rozowe_studio_get_photography_page_url(); ?>">Fotografia</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php echo get_post_type_archive_link('post'); ?>">
                        <?php echo rozowe_studio_get_posts_page_title(); ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php the_title(); ?>
                </li>
            </ol>
        </nav>

        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <div class="entry-meta">
                        <span class="posted-on">
                            <?php echo get_the_date(); ?>
                        </span>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Strony:', 'rozowe-studio'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>

                <footer class="entry-footer">
                    <nav class="post-navigation">
                        <div class="nav-previous">
                            <?php previous_post_link('%link', '&laquo; Poprzedni reportaż'); ?>
                        </div>
                        <div class="nav-next">
                            <?php next_post_link('%link', 'Następny reportaż &raquo;'); ?>
                        </div>
                    </nav>
                    
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
