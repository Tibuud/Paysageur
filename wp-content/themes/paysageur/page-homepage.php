<?php

get_header(); ?>
<main class="container-fluid conteneur-principal conteneur-homepage">

	<?php

    $image = get_field('image_bg');

    if (!empty($image)): ?>

		<section class='bg-principal' style="background-image: url(<?php echo $image['url']; ?>);">

	<?php else: ?>
		<section class='bg-principal'>
	<?php endif; ?>
		<h1 class="text-center"><?php echo get_bloginfo('name'); ?></h1>
		<h2 class="text-center"><?php the_field('sous-titre') ?></h2>
	</section>
	<section class="row manifeste"> <!-- Zone manifeste -->
		<div class='text-center manifeste-contenu' id='manifeste'>
			<?php
            $image = get_field('image_manifeste');
            if (!empty($image)): ?>
				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
			<?php endif; ?>
			<h3><?php the_field('titre_manifeste') ?></h3>
			<p class='text-left'><?php the_field('texte_manifeste') ?>
			</p>
		</div>
	</section> <!-- fin de la zone manifeste -->
	<section class='decouverte'> <!-- début section découverte -->
		<div class='deco-decouverte'></div> <!-- Div pour trait de séparation -->
		<div class='row'>
		<h3>
			<?php
            $fr_en = ['Découvrez paysageur', 'Discover paysageur'];
    do_action('tb_auto_traduction', $fr_en); ?>
		</h3>
		</div>
		<div class="text-center d-flex justify-content-around flex-wrap">
			<?php
            $args = array(
                'post_type' => 'revue',
                'posts_per_page'=>4,
                'order'=>'DESC',
                'orderby'=>'ID',
                );

                $the_query = new WP_Query($args);

                // The Loop
                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post(); ?>
						<div class="d-inline-flex p-2">
							<div>

							<?php $image = get_field('couverture');
                        if (!empty($image)): ?>
							<div>
								<a href=" <?php the_permalink(); ?> "><img  class="img-fluid" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" /></a>
							</div>
							<div>
								<a href="<?php the_permalink(); ?>">
									<button type="button" class="btn btn-primary">
										<?php $fr_en = ['Découvrir', 'Discover'];
                        do_action('tb_auto_traduction', $fr_en); ?>
									</button>
								</a>
							</div>
							<div>
								<a href="<?php echo get_permalink(6); ?>">
									<button type="button" class="btn btn-primary">
								<?php $fr_en = ['Acheter', 'Buy'];
                        do_action('tb_auto_traduction', $fr_en); ?>
									</button>
								</a>
							</div>
						</div>
					</div>
						<?php endif;
                    }
                    wp_reset_postdata();
                } ?>
		</div>
	</section><!-- fin section découverte -->
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
				<li data-target="#carouselExampleIndicators" data-slide-to="<?php $compteur ?>" class="<?php if ($compteur == 0) : echo "active"; endif; ?>"></li>
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
	<?php

$posts = get_field('revue_liee');

        if ($posts): ?>
		    <?php foreach ($posts as $post): // variable must be called $post (IMPORTANT)?>
		        <?php setup_postdata($post); ?>

		<section class='presentation-numero' style="background-color:<?php the_field('couleur'); ?>"> <!-- Début section présentation -->
			<h3>n°<?php the_field('numero') ?></h3>
			<h4><?php the_field('sous-titre'); ?></h4>
			<div class='deco-presentation'></div> <!-- Div pour trait de séparation -->
			<p><?php the_field('description') ?></p>
			<div><a href="<?php echo get_permalink(6); ?>"><button type="button" class="btn btn-primary">
				<?php
                $fr_en = ['Acheter', 'Buy'];
        do_action('tb_auto_traduction', $fr_en); ?>
			</button></a></div>
		</section><!-- fin section présentation -->
		    <?php endforeach; ?>
		    <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly?>
		<?php endif; ?>
	<section class='extraits'> <!--Section des articles -->
		<h3><?php
        $fr_en = ['Extraits', 'Excerpts'];
        do_action('tb_auto_traduction', $fr_en);
        ?></h3>
		<?php

    $posts = get_field('articles_lies');

            if ($posts):
                $compteur = 0 ?>
				<?php foreach ($posts as $post): // variable must be called $post (IMPORTANT)?>
					<?php setup_postdata($post); ?>
					<?php $compteur +=1;
                    if ($compteur == 1) : ?>
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
<?php
$args = array(
    'post_type' => 'revue',
    'posts_per_page'=>1,
    'order'=>'DESC',
    'orderby'=>'ID',
    );

        $the_query = new WP_Query($args);

        // The Loop
        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post(); ?>
				<div class="text-center">
					<a href="<?php the_permalink(); ?>"><button type="button" class="text-center btn btn-primary">
						<?php $fr_en = ['Voir tous les extraits du dernier numéro', 'See the excerpts of the last numero'];
                do_action('tb_auto_traduction', $fr_en); ?>
					</button></a>
				</div>
			<?php
            }
            wp_reset_postdata();
        } ?>
	</section> <!-- fin de section des articles -->
	<section class='snapwidget'><!-- début de section du snapwidget -->
		<h3>#Paysageur</h3>
		<p><?php $fr_en = ['Suivez nous sur Instagram !', 'Follow us on Instagram !'];
do_action('tb_auto_traduction', $fr_en); ?></p>
	<!-- SnapWidget -->
		<iframe src="https://snapwidget.com/embed/520691" class="snapwidget-widget" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%; "></iframe>
	</section> <!-- fin de section du snapwidget -->
</main>

<?php

get_footer();
