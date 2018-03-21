<footer class="container-fluid">
		<div class='row illu'>
			<img src="<?php echo esc_url(get_template_directory_uri()) . '/assets/css/img/grande_lande.png' ; ?>" alt="">
		</div>
		<div class='row justify-content-around sous-illu'>
			<div class="col-lg text-center">
				<p>Suivez nous sur :</p>
				<ul class="list-inline">
			   <a class="text-xs-center" target="_blank" href="https://www.facebook.com/PaysageurMagazine/"><li class="social list-inline-item"><i class="fab fa-facebook-f"></i></li></a>
			   <a class="text-xs-center" target="_blank" href="https://www.instagram.com/revue_paysageur/?hl=fr"><li class="social list-inline-item"><i class="fab fa-instagram"></i></li></a>
			   <a class="text-xs-center" target="_blank" href="https://twitter.com/revue_paysageur?lang=fr"><li class="social list-inline-item"><i class="fab fa-twitter"></i></li></a>
			</ul>
			</div>
			<div class="col-lg text-center">
				<p>Newsletter</p>
				<div class="row justify-content-center">
					<div class="col-md-auto">
						<?php $form_widget = new \MailPoet\Form\Widget();
                        echo $form_widget->widget(array('form' => 1, 'form_type' => 'php'));
                        ?>
					</div>
					<div class="col-lg-auto">
					</div>
				</div>
			</div>
			<div class="col-lg text-center">
				<div class="asso">
					<p>Association Paradisier Vert<br>8, rue Mélingue - 75019 Paris<br>06.02.06.74.72</p>
				</div>
			</div>
		</div>
		<div class="row justify-content-between bas-page">
			<div class="col-lg-auto">
				<div class="row justify-content-center order-lg-last text-center">
				   <a class='col-md-auto' href="<?php echo get_permalink(56); ?>"><?php echo get_the_title(56); ?></a>
				   <a class='col-md-auto' href="<?php echo get_permalink(6); ?>">Points de vente</a>
				   <a class='col-md-auto' href="<?php echo get_permalink(79); ?>"><?php echo get_the_title(79); ?></a>
				   <a class='col-md-auto' href="<?php echo get_permalink(81); ?>"><?php echo get_the_title(81); ?></a>
				   <a class='col-md-auto' href="<?php echo get_permalink(83); ?>"><?php echo get_the_title(83); ?></a>
			   </div>
			</div>
			<div class="col-lg-auto text-center order-lg-first">
				<p>&copy; Paysageur 2018 - Tous Droits Réservés</p>
			</div>
		</div>
</footer>
	<!-- Optional JavaScript -->
	<!-- jQuery first, then Bootstrap JS -->
	<script src="https://snapwidget.com/js/snapwidget.js"></script> <!-- A n'activer que sur la homepage -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBJZT3YuA1G-z2XIr4S7_SXW7G9qQ66CXE"></script><!-- A n'activer que sur contact -->
	<script type="text/javascript" src='<?php echo esc_url(get_template_directory_uri()) . '/assets/js/google-map.js' ; ?>'></script>
	<script type="text/javascript" src='<?php echo esc_url(get_template_directory_uri()) . '/assets/js/script.js' ; ?>'></script>

	<?php wp_footer(); ?>
</body>

</html>
