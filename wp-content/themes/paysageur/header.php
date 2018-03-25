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
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- <link rel="profile" href="http://gmpg.org/xfn/11"> -->
	<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()) . '/assets/css/google-map.css' ; ?>">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()) . '/assets/css/style.css' ; ?>">
        <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT:400,400i" rel="stylesheet">
	<?php wp_head();
    if (is_admin_bar_showing()) : ?>
<style type="text/css" media="screen">
.fixed-top{
    top:32px;
}
</style>
<?php endif; ?>
</head>

<body>


	<header>
		<nav class="navbar fixed-top navbar-expand-lg navbar-light">
                <?php the_custom_logo(); ?>
                <a class="navbar-brand d-none d-xl-block" href="<?php bloginfo('url'); ?>"><img src="<?php echo esc_url(get_template_directory_uri()) . '/assets/css/img/logo_typo_paysageur.png' ; ?>" alt="Logo paysageur 2" class="img-rounded center-block"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto d-lg-flex d-block flex-row mx-lg-auto mx-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php bloginfo('url'); ?>">
							<?php
                            $fr_en = ['Accueil', 'Home'];
                            do_action('tb_auto_traduction', $fr_en);
                            ?>
							</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?php
                                $fr_en = ['Numéro', 'Numero'];
                                do_action('tb_auto_traduction', $fr_en);
                                ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<?php
                                $args = array('post_type' => 'revue');

                                        $the_query = new WP_Query($args);

                                        if ($the_query->have_posts()) {
                                            while ($the_query->have_posts()) {
                                                $the_query->the_post(); ?>
												<a class="dropdown-item" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											<?php
                                            }
                                            wp_reset_postdata();
                                        } ?>

                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo get_permalink(53); ?>"><?php echo get_the_title(53); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo get_permalink(6); ?>"><?php echo get_the_title(6); ?></a>
                        </li>
                    </ul>
                    <ul class="pull-right navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="<?php echo get_permalink(7); ?>"><i class="fas fa-shopping-basket"></i></a>
							<?php
                                if (function_exists('paysageur_woocommerce_header_cart')) {
                                    paysageur_woocommerce_header_cart();
                                }
                            ?>
                        </li>
						<nav id="site-navigation" class="main-navigation">

						            <?php
                                    wp_nav_menu(array(
                                        'theme_location' => 'menu-1',
                                        'menu_id'        => 'primary-menu',
                                    ));
                                    ?>
						        </nav><!-- #site-navigation -->
					</ul>
        </nav>
	</header><!-- #masthead -->
