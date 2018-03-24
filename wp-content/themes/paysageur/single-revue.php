<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Paysageur_Theme
 */

get_header();
?>

<main class="container-fluid conteneur-principal">
			<?php

        $image = get_field('image_de_fond');

        if (!empty($image)): ?>

			<section class='top-numero' style="background-image: url(<?php echo $image['url']; ?>);">

		<?php else: ?>

			<section class='top-numero'>

		<?php endif; ?>

			<h1>N°<?php if (strlen(get_field('numero')) == 1) {
            echo "0";
        }
                the_field('numero'); ?></h1>
			<h2><?php the_field('sous-titre'); ?></h2>
		</section>
		<section class='resume-numero row'> <!-- début de section resumé numéro -->
			<p class=col-md-9><?php the_field('description_longue'); ?>
			<div class="col-md-3">
				<?php $posts = get_field('produit_lie');
                        if ($posts): ?>
						    <?php foreach ($posts as $post): // variable must be called $post (IMPORTANT)
                                 setup_postdata($post); ?>
									<ul>
                                    <?php wc_get_template_part('content', 'product'); ?>
									</ul>
									<?php endforeach;
                                    wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly?>
									<?php else: ?>
										<?php $image = get_field('couverture');
                                    if (!empty($image)): ?>
											<img class='img-fluid' src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
									<?php endif; ?>
									<h3><?php the_field('sous-titre'); ?></h3>
									<p><?php $fr_en = ['Numéro', 'Numero'];
                            do_action('tb_auto_traduction', $fr_en); ?> <?php the_field('numero'); ?> - <?php the_field('saison'); ?></p>
									<p><?php $fr_en = ['Bientot disponible !', 'Coming soon !'];
                            do_action('tb_auto_traduction', $fr_en); ?></p>
									<?php endif; ?>
			</div>

		</section><!-- fin de section resumé numéro -->
		<section class='slider'> <!--Slider -->
			<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators">
					<?php
                    //Get the images ids from the post_metadata
                    $images = acf_photo_gallery('carousel', $post->ID);
                    //Check if return array has anything in it
                    if (count($images)):
                        //Cool, we got some data so now let's loop over it
                        $compteur = 0;
                        foreach ($images as $image): ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $compteur ?>" class="<?php if ($compteur == 0) : echo "active"; endif; ?>"></li>
					<?php $compteur +=1;
                endforeach; endif; ?>
				</ol>
				<div class="carousel-inner" role="listbox">
					<?php
                    //Get the images ids from the post_metadata
                    $images = acf_photo_gallery('carousel', $post->ID);
                    //Check if return array has anything in it
                    if (count($images)):
                        //Cool, we got some data so now let's loop over it
                        $compteur = 0;
                        foreach ($images as $image):
                            $title = $image['title']; //The title
                            $full_image_url= $image['full_image_url']; //Full size image url
                            ?>
							<div class="carousel-item <?php if ($compteur == 0) : echo "active"; endif; ?>">
								<img class="d-block img-fluid" src="<?php echo $full_image_url; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>">
							</div>
						<?php $compteur = 1;
                    endforeach; endif; ?>
				</div>
				<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			</div>
		</section> <!--fin du slider -->
		<section class='extraits extraits-numero'> <!--Section des articles -->
				<h3><?php
                $fr_en = ['Extraits', 'Excerpts'];
                do_action('tb_auto_traduction', $fr_en);
                ?></h3>
				<?php

            $posts = get_field('articles_lies');

                    if ($posts):
                        $compteur = 0; ?>
						<?php foreach ($posts as $post): // variable must be called $post (IMPORTANT)?>
							<?php setup_postdata($post); ?>
							<?php $compteur +=1;
                            if ($compteur%2 == 1) : ?>
								<article class="row">
									<div class='col-md-6 extrait-1'>
										<h4><?php the_title(); ?></h4>
										<p><?php the_field('extrait'); ?></p>
									</div>
									<div class='text-center col-md-6 extrait-1-img-1'>
									<?php $image = get_field('image');
                                if (!empty($image)): ?>
										<img class='img-fluid' src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
									</div>
								<?php endif; ?>
								</article>
							<?php else: ?>
								<article class="row">
									<div class='text-center col-md-6 order-md-first order-last extrait-2-img-2'>
									<?php $image = get_field('image');
                                if (!empty($image)): ?>
										<img class='img-fluid' src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
								<?php endif; ?>
									</div>
									<div class='col-md-6 order-md-last order-first extrait-2'>
										<div class="extrait-2-deco"></div>
										<h4><?php the_title(); ?></h4>
										<p><?php the_field('extrait'); ?></p>
									</div>
								</article>
								<?php endif; ?>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly?>
					<?php endif; ?>
		</section> <!-- fin de section des articles -->
		<section class='contributeurs'> <!-- début de section contributeurs -->
			<h3><?php
            $fr_en = ['Contributeurs', 'Contributors'];
            do_action('tb_auto_traduction', $fr_en);
            ?></h3>
			<div class="text-center d-flex justify-content-around flex-wrap">
			<?php

        $posts = get_field('contributeurs_lies');

                if ($posts): ?>
				    <?php foreach ($posts as $post): // variable must be called $post (IMPORTANT)?>
				        <?php setup_postdata($post); ?>
						<article class="d-inline-flex p-2">
												<div>
													<?php $image = get_field('image');
                                                if (!empty($image)): ?>
														<img class='img-fluid' src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
												<?php endif; ?>
													<h4><?php the_field('nom') ?></h4>
													<p><?php the_field('description') ?></p>
													<div class="lien_contrib"><a href="<?php the_field('lien') ?>" target='_blank'><?php the_field('nom_lien') ?></a></div>
												</div>
											</article>
				    <?php endforeach; ?>
				    <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly?>
				<?php endif; ?>
			</div>
		</section> <!-- fin de section contributeurs -->
		<section class='produits-page-numero'> <!-- debut section produit de la page revue -->
			<h3>Produits</h3>
			<div class="text-center d-flex justify-content-around flex-wrap">
				<?php
                $args = array(
                    'post_type' => 'product',
                    'product_cat' => 'derive',
                    'posts_per_page'=>3,
                    'order'=>'DESC',
                    'orderby'=>'ID',
                    );

                        $the_query = new WP_Query($args);

                        // The Loop
                        if ($the_query->have_posts()) {
                            while ($the_query->have_posts()) {
                                $the_query->the_post(); ?>
								<article class="d-inline-flex p-2">
									<ul>

								<?php wc_get_template_part('content', 'product'); ?>
							</ul>
								</article>
							<?php
                            }
                            wp_reset_postdata();
                        } ?>
			</div>
		</section> <!-- fin section produit de la page revue -->
	</main>

<?php
get_footer();
