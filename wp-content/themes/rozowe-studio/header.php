<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Sticky Navbar outside of #page for better sticky behavior -->
<?php rozowe_studio_display_navbar(); ?>

<script>
// Fallback sticky navbar JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.custom-navbar');
    if (!navbar) return;
    
    const navbarHeight = navbar.offsetHeight;
    
    function handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > navbarHeight) {
            navbar.classList.add('is-sticky');
        } else {
            navbar.classList.remove('is-sticky');
        }
    }
    
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initial check
});
</script>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'rozowe-studio'); ?></a>

    <div id="content" class="site-content"> 