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
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         ';ys+pG(L3:2s]@TE)bP+[Jc8DA T{cL5xYN=_Cy!^(RS7+6fj-WZ@hmIrU5o05EI');
define('SECURE_AUTH_KEY',  ' 4B04,o)?C%DBeW[.A6<|sDq6fx]{.fYpo`X4<|f~~Pw>oXB$#Ln:)WdH4g<{m>p');
define('LOGGED_IN_KEY',    '7XP|UNHJMuAC%?)jj e:70k-_55W-[8yt1w]JEps=HCe]TnMw!^rLl)|%-Sp|i,A');
define('NONCE_KEY',        '(4^HCS<dI,kod|_9}-YZ: $bSw^W}tuI%%; G{/|5+Ycalk%3cZ|i}C@D}=ENp/O');
define('AUTH_SALT',        'jC+Yjo:9-HR7H_KAaLXN=]FPkB_=)aGTL0KcKg#1+$K04Wl,_X?mK9$xb:(CI/Hc');
define('SECURE_AUTH_SALT', 'pAPG6[$ BlZ-Q%aN- y&G8dxh2MM+4|u`y_-;%SC#j${W75:w.=mgpPvT&KiWJ`r');
define('LOGGED_IN_SALT',   '&q(WZcfZYE!Mw$L=!^?2hSg72Anek+?M=!78ZzA#V]aIeFF>PkS3f4IH3-KP6Vbp');
define('NONCE_SALT',       'Yjw3q5@,5N`G#FKVQ`+DcPQ4P3iBa5b#p8a3yliu|wLrXWq+<+7+%XP}]1anc/0=');
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