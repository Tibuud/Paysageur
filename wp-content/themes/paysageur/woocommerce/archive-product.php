<?php
if (! defined('ABSPATH')) {
    exit;
}

get_header(); ?>
<main class="container-fluid conteneur-principal">
        <section class='sous-menu navbar navbar-expand-lg navbar-light'>
                <div  id='hook-edition' class="d-flex justify-content-around">
                   <a class='' href="#hook-produits">
                       <?php
                       $fr_en = ['Produits', ' Products'];
                       do_action('tb_auto_traduction', $fr_en);
                       ?>
                   </a>
                   <a class='' href="#hook-points_de_vente">
                       <?php
                       $fr_en = ['Points de Vente', ' Sales Location'];
                       do_action('tb_auto_traduction', $fr_en);
                       ?>
                   </a>

               </div>

        </section>
        <section class='produits-page-boutique'> <!-- debut section produit de la page revue -->
            <h3 id='hook-produits'>
                <?php
                $fr_en = ['Produits', ' Products'];
                do_action('tb_auto_traduction', $fr_en);
                ?>
            </h3>
            <div class="text-center d-flex justify-content-around flex-wrap">
            <?php
            if (have_posts()) {
                woocommerce_product_loop_start();

                if (wc_get_loop_prop('total')) {
                    while (have_posts()) {
                        the_post();
                        do_action('woocommerce_shop_loop');
                        wc_get_template_part('content', 'product');
                    }
                }
                woocommerce_product_loop_end();
                do_action('woocommerce_after_shop_loop');
            } else {
                do_action('woocommerce_no_products_found');
            } ?>
            </div>
        </section> <!-- fin section produit de la page revue -->
        <section class='section_points-de-vente'>
            <h3 id='hook-points_de_vente'>
                <?php
                $fr_en = ['Points de Vente', ' Sales Location'];
                do_action('tb_auto_traduction', $fr_en);
                ?>
			</h3>
<?php
 $terms_pays = get_terms(array(
    'taxonomy' => 'pays',
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => false,

 ));

 $terms_ville = get_terms(array(
     'taxonomy' => 'ville',
     'orderby' => 'name',
     'order' => 'ASC',
     'hide_empty' => false,
 ));

 foreach ($terms_pays as $value) {
     $pays_slug = $value->slug;
     $pays_name = $value->name;

     foreach ($terms_ville as $value) {
         $ville_slug = $value->slug;
         $ville_name = $value->name;
         $args = array(
             'post_type' => 'points_de_vente',
             'tax_query' => array(
                 array(
                     'taxonomy' => 'ville',
                     'field'    => 'slug',
                     'terms'    => $ville_slug,
                 ),
                 array(
                     'taxonomy' => 'pays',
                     'field'    => 'slug',
                     'terms'    => $pays_slug,
                 ),
             ),
          );

         $the_query = new WP_Query($args);
         if (isset($liste_pays)) {
             if (($the_query->have_posts()) && !in_array($pays_slug, $liste_pays)) {
                 echo "<h4>" . $pays_name . "</h4>";
             }
         } elseif ($the_query->have_posts()) {
             echo "<h4>" . $pays_name . "</h4>";
         }

         if (isset($liste_villes)) {
             if (($the_query->have_posts()) && !in_array($ville_slug, $liste_villes)) {
                 echo "<p class='ville'>" . $ville_name . "</p>";
                 echo "<div class='d-flex flex-wrap justify-content-start'>";
             }
         } elseif ($the_query->have_posts()) {
             echo "<p class='ville'>" . $ville_name . "</p>";
             echo "<div class='d-flex flex-wrap justify-content-start'>";
         }

         if ($the_query->have_posts()) {
             while ($the_query->have_posts()) {
                 $the_query->the_post(); ?>
                 <div class="d-flex flex-wrap justify-content-start">
                     <div class='point-de-vente'>
                         <p><?php the_field("nom_de_la_boutique"); ?></p>
                         <p><?php the_field("adresse"); ?></p>
                         <p><?php the_field("code_postal"); ?> <?php echo $ville_name ?></p>
                         <p><?php the_field("numero"); ?></p>
                         <p><a href="#"><?php the_field("lien"); ?></a><?php the_field("nom_lien"); ?></p>
                     </div>
                 </div>
             <?php
             }

             if (isset($liste_villes)) {
                 if (($the_query->have_posts()) && !in_array($ville_slug, $liste_villes)) {
                     echo "</div>";
                 }
             } elseif ($the_query->have_posts()) {
                 echo "</div>";
             }

             $liste_villes[] = $ville_slug;
             $liste_pays[] = $pays_slug;
             wp_reset_postdata();
         }
     }
 } ?>
 </section>
 </main>
 <?php
get_footer();
