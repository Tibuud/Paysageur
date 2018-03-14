<?php add_action('init', 'tb_create_revue_tax');

function tb_create_revue_tax()
{
    register_taxonomy(
    'numero_revue',
    ['revue', 'post', 'collaborateur'],
    [
      'label' => 'Numero de la revue',
      'rewrite' => array('slug' => 'numero_revue'),
      'hierarchical' => false,
    ]
  );
}
