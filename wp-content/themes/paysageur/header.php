<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Paysageur_Theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()) . '/assets/css/google-map.css' ; ?>">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()) . '/assets/css/style.css' ; ?>">
	<?php wp_head(); ?>
</head>

<body>

<div>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'paysageur'); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
            the_custom_logo();
            if (is_front_page() && is_home()) :
                ?>
				<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
				<?php
            else :
                ?>
				<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
				<?php
            endif;
            $paysageur_description = get_bloginfo('description', 'display');
            if ($paysageur_description || is_customize_preview()) :
                ?>
				<p class="site-description"><?php echo $paysageur_description; /* WPCS: xss ok. */ ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e('Primary Menu', 'paysageur'); ?></button>
			<?php
            wp_nav_menu(array(
                'theme_location' => 'menu-1',
                'menu_id'        => 'primary-menu',
            ));
            ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
	<?php
        if (function_exists('paysageur_woocommerce_header_cart')) {
            paysageur_woocommerce_header_cart();
        }
    ?>
	<div id="content" class="site-content">
