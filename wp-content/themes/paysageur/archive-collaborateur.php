<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Paysageur_Theme
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

    <?php  if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
              <h1><?php

              the_field("nom"); ?></h1>


          <?php

          foreach (get_field("articles") as $key) {
              echo "<a href='" . $key->guid . "'><h2>Lien vers " . $key->post_title . "</a>";
          }
    }
}


?>






		</main><!-- #main -->
	</div><!-- #primary -->

<?php
// get_sidebar();
get_footer();
