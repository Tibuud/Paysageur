<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'paysageur_wp');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '@F88$C|~-8Gi~myDI<6`yO@mrW[E-;Ty[g+8J<esn{t:f`=CV;z&,{]>>h0Rr?Zt');
define('SECURE_AUTH_KEY',  '&fr6.$h~}?HzuK<>P=2B*n,eFttF1 b_xC89RsURXQdWM,&;1d<1!Ji&kJyQ Du1');
define('LOGGED_IN_KEY',    'en!0gYo@b2Z|02M$H|ObR[_nZI8)Zk5P>::EHG_1pS$ *x*luY!0f?c@)>lq8rPZ');
define('NONCE_KEY',        '38#PV>*~0q;=hkB8m%a/_G$nK<Hrz[L2S <L5a;CmV8rNd}C(R:8pa.7z=csYE1:');
define('AUTH_SALT',        'Jcb_eXcn`y+sBVLHJAv`oK4J1[NllD]R_YIThXZ7,;%2_/v;66&.#|W*]g|I:x$s');
define('SECURE_AUTH_SALT', 'v~2i4J)WX/xVtSqxR)s!VNB4(<pCm)^4o$0kFp.6#CM^7j#jH_S@LBrF[fgP|N6P');
define('LOGGED_IN_SALT',   'mgUT^$YeXqOTh>$LnRwp!)45.5 C779y1+6hJOVp^y):&i{11$PMr4BvIr*HSW?Z');
define('NONCE_SALT',       '|G5pMjMg28(FI=~5`QG27/F0C:@bC/WhUTCfnwPkqMj9a6eR~=CQRyX#K|o;3YKz');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'pa_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');