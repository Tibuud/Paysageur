<?php get_header(); ?>

<main class="container-fluid conteneur-principal">
    <section class='sous-menu navbar navbar-expand-lg navbar-light'>
            <div  id='hook-edition' class="d-flex justify-content-around">
               <a class='' href="#hook-edition"><?php
               $fr_en = ['Éditions Paradisier', 'Paradisier Editions'];
               do_action('tb_auto_traduction', $fr_en);
               ?></a>
               <a class='' href="#hook-fondateurs"><?php
               $fr_en = ['Fondateurs', 'Fondators'];
               do_action('tb_auto_traduction', $fr_en);
               ?></a>
               <a class='' href="#hook-collaborateurs"><?php
               $fr_en = ['Collaborateurs', 'Collaborators'];
               do_action('tb_auto_traduction', $fr_en);
               ?></a>
               <a class='' href="#hook-remerciements"><?php
               $fr_en = ['Remerciements', 'Thanks'];
               do_action('tb_auto_traduction', $fr_en);
               ?></a>
           </div>

    </section>
    <?php

$image = get_field('image_de_fond');

if (!empty($image)): ?>

    <section class='top-paradisier' style="background-image: url(<?php echo $image['url']; ?>);">

<?php else: ?>

    <section class='top-paradisier'>

<?php endif; ?>

        <?php if (count(explode(' ', get_field('titre') == 2))) :
            $titre = explode(' ', get_field('titre')); ?>
            <h1><?php echo $titre[0]; ?>
            <span><?php echo $titre[1]; ?></span></h1>
            <?php else: ?>
            <h1><?php the_field('titre'); ?></h1>
            <?php endif; ?>
    </section>
    <section class="row manifeste manifeste-paradisier" id='manifeste-paradisier'> <!-- Zone manifeste -->
        <div>
            <p><?php the_field('description'); ?></p>
        </div>
    </section> <!-- fin de la zone manifeste -->
    <section  class='fondateurs'> <!-- début de section fondateurs -->
        <h3 id='hook-fondateurs'><?php
        $fr_en = ['Fondateurs', 'Fondators'];
        do_action('tb_auto_traduction', $fr_en);
        ?></h3>
        <div class="text-center d-flex justify-content-around flex-wrap">
            <article class="d-inline-flex p-2">
                <div>
                    <?php
                    $image = get_field('image_fondateur_1');
                    if (!empty($image)): ?>
                        <div><img class="img-fluid" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>"></div>
                    <?php endif; ?>
                    <h4><?php the_field('nom_fondateur_1'); ?></h4>
                    <p><?php the_field('description_fondateur_1'); ?></p>
                    <div><a href="<?php the_field('lien_fondateur_1') ?>" target='_blank'><?php the_field('nom_lien_fondateur_1') ?></a></div>
                </div>
            </article>
            <article class="d-inline-flex p-2">
                <div>
                    <?php
                    $image = get_field('image_fondateur_2');
                    if (!empty($image)): ?>
                        <div><img class="img-fluid" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>"></div>
                    <?php endif; ?>

                    <h4><?php the_field('nom_fondateur_2'); ?></h4>
                    <p><?php the_field('description_fondateur_2'); ?></p>
                    <div><a href="<?php the_field('lien_fondateur_2') ?>" target='_blank'><?php the_field('nom_lien_fondateur_2') ?></a></div>
                </div>
            </article>
        </div>
    </section> <!-- fin de section fondateur -->
    <section class='collaborateurs'> <!-- début de section collaborateurs -->
        <h3 id='collaborateurs'><?php
        $fr_en = ['Collaborateurs', 'Collaborators'];
        do_action('tb_auto_traduction', $fr_en);
        ?></h3>
        <div class="text-center d-flex justify-content-around flex-wrap">
            <?php
            $args = array(
                'post_type' => 'collaborateur',
                'order'=>'ASC',
                );

                    $the_query = new WP_Query($args);

                    // The Loop
                    if ($the_query->have_posts()) {
                        while ($the_query->have_posts()) {
                            $the_query->the_post(); ?>
                            <article class="d-inline-flex p-2">
                                <div>
                                    <?php
                                    $image = get_field('image');
                            if (!empty($image)): ?>
                                        <div><img class="img-fluid" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>"></div>
                                    <?php endif; ?>
                                    <h4><?php the_field('nom'); ?></h4>
                                    <p><?php the_field('description'); ?></p>
                                    <div class="lien_collab"><a href="<?php the_field('lien') ?>" target='_blank'><?php the_field('nom_lien') ?></a></div>
                                </div>
                            </article>
                    <?php
                        }
                        wp_reset_postdata();
                    } ?>
        </div>
    </section> <!-- fin de section collaborateurs -->
    <section class="remerciements"> <!-- Début section remerciements -->
        <h3 id='hook-remerciements'><?php
        $fr_en = ['Remerciements', 'Thanks'];
        do_action('tb_auto_traduction', $fr_en);
        ?></h3>
        <div class='remerciements-deco'></div>
        <p><?php the_field('message_remerciement'); ?></p>
    </section> <!-- Fin section remerciements -->
    <section class="backers"> <!-- Début section remerciements -->
        <p><?php the_field('nuage_de_bakers'); ?></p>
    </section> <!-- Fin section remerciements -->


</main>

<?php
get_footer();
