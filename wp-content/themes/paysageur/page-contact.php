<?php get_header(); ?>

<main class="container-fluid conteneur-principal">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <h1 class="titre-section"><?php the_title(); ?></h1>
    <section class='row d-flex contact'>
        <div class='col-md order-md-last formulaire'>
            <?php the_content()?>
        </div>
        <div class='col-md order-md-first map-google'>
            <?php

            $location = get_field('location');

            if (!empty($location)):
            ?>
            <div class="acf-map">
            	<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
            </div>
            <?php endif; ?>
            <div class="asso-contact">
                <h2>Association Paradisier Vert</h2>
                <p>8, rue Mélingue - 75019 Paris<br>06.02.06.74.72</p>
            </div>
        </div>
    </section>
</main>

<?php
endwhile; endif;
get_footer();
