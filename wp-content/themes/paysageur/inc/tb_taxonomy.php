<?php add_action('init', 'tb_create_tax');

function tb_create_tax()
{
    register_taxonomy(
    'ville',
    ['points_de_vente'],
    [
      'label' => 'Ville',
      'rewrite' => array('slug' => 'ville'),
      'hierarchical' => false,
    ]
  );

    register_taxonomy(
  'pays',
  ['points_de_vente'],
  [
    'label' => 'Pays',
    'rewrite' => array('slug' => 'pays'),
    'hierarchical' => false,
  ]
);
}
