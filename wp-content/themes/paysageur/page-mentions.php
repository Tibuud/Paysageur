<?php get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<main class="container-fluid conteneur-principal">
    <section class='row mention'>
    <article class="col-md">
        <h1>Paysageur est édité <br>par les Éditions Paradisier</h1>
        <h2>SIRET</h2>
        <p><?php the_field('siret') ?></p>
        <h2>Siège social</h2>
        <p><?php the_field('siege') ?></p>
    </article>
    <article class="col-md">
        <h2>Directeur de publication</h2>
        <p><?php the_field('directeur') ?></p>
        <h2>Hébergement du site</h2>
        <p class='hebergement'><?php the_content()?></p>
    </article>
</section>
</main>

<?php
endwhile; endif;
get_footer();
