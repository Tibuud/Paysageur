<?php get_header(); ?>

<main class="container-fluid conteneur-principal">

    <section class='row d-flex justify-content-center cgv'>
        <div class='col'>


        <h1><?php echo get_the_title(81);?></h1>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div>
                <?php the_content() ?>
            </div>
        </div>
    </section>
</main>

<?php
endwhile; endif;
get_footer();
