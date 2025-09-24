<?php
/**
 * The template for displaying archive pages (posts listing)
 *
 * @package Różowe_Studio
 */

get_header(); ?>

<main id="main" class="site-main archive-page">
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
                    <?php echo rozowe_studio_get_posts_page_title(); ?>
                </li>
            </ol>
        </nav>

        <header class="page-header">
            <h1 class="page-title"><?php echo rozowe_studio_get_posts_page_title(); ?></h1>
            <?php 
            $posts_page_id = get_option('page_for_posts');
            $page_description = $posts_page_id ? rozowe_studio_get_page_description($posts_page_id) : '';
            
            if ($page_description) : ?>
                <p class="page-description"><?php echo esc_html($page_description); ?></p>
            <?php else : ?>
                <p class="page-description">Odkryj nasze najpiękniejsze sesje ślubne</p>
            <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="posts-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                        <a href="<?php the_permalink(); ?>" class="post-card-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>');">
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-overlay">
                                <h2 class="post-title"><?php the_title(); ?></h2>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '&laquo; Poprzednia',
                'next_text' => 'Następna &raquo;',
            ));
            ?>
            
        <?php else : ?>
            <div class="no-posts">
                <h2>Nie znaleziono reportaży</h2>
                <p>Przepraszamy, ale nie znaleziono żadnych reportaży ślubnych.</p>
            </div>
        <?php endif; ?>

        <?php
        // Display Gutenberg blocks from the posts page
        $posts_page_id = get_option('page_for_posts');
        if ($posts_page_id) {
            $posts_page = get_post($posts_page_id);
            if ($posts_page && !empty($posts_page->post_content)) {
                echo '<div class="page-content-after-posts">';
                echo '<div class="container">';
                echo apply_filters('the_content', $posts_page->post_content);
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</main>

<?php get_footer(); ?>
