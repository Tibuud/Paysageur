<?php

/*
Ajout des customs post type dans la table de SQL
*/
add_action('init', 'tb_create_post_type');

function tb_create_post_type()
{

  // définir le custom_post_type
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

    register_post_type(
      'collaborateur',
   [
      'labels' => [
        'name' => 'Collaborateurs',
        'singular_name' => 'Collaborateur'
        ],

      'public' => true,
      'has_archive' => true
   ]
  );

    register_post_type(
    'contributeur',
 [
    'labels' => [
      'name' => 'Contributeurs',
      'singular_name' => 'Contributeur'
      ],

    'public' => true,
    'has_archive' => true
 ]
);

    register_post_type(
      'points_de_vente',
    [
      'labels' => [
        'name' => 'Points de vente',
        'singular_name' => 'Point de vente'
        ],

      'public' => true,
      'has_archive' => true
    ]
    );
}
