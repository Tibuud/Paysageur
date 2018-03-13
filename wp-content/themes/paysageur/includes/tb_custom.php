<?php
add_action('init', 'tb_create_post_type');

function tb_create_post_type()
{

  // définit le custom (post_type dans la table wp_posts MySQL)
    register_post_type(
      'revue',
   [
       // les labels permettent d'afficher des informations dans le CMS
      'labels' => [
        'name' => 'Revues',
        'singular_name' => 'Revue'
        ],

      'public' => true, // pour qu'il s'affiche dans l'administration
      // le support permet de définir les attributs du contenu
      // ici nous il aura un titre, un éditeur et des images en avant
      'has_archive' => true // visible dans les archives
   ]
  );
}
