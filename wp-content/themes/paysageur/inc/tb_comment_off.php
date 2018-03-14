<?php
//Désactivation de la prise en charge des commentaires et des rétrolien dans les posts
function _disable_comments_post_types_support()
{
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'tb_disable_comments_post_types_support');

//Fermeture des commentaires sur le front-end (inutile ?)
// function tb_disable_comments_status()
// {
//     return false;
// }
// add_filter('comments_open', 'tb_disable_comments_status', 20, 2);
// add_filter('pings_open', 'tb_disable_comments_status', 20, 2);
//

// Suppression des page de commentaire du menu d'administration
function tb_disable_comments_admin_menu()
{
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'tb_disable_comments_admin_menu');


//Suppression de la metabox du dashboard
function tb_disable_comments_dashboard()
{
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'tb_disable_comments_dashboard');

//Suppression du lien vers les commentaires dans l'administration
function tb_disable_comments_admin_bar()
{
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('init', 'tb_disable_comments_admin_bar');
