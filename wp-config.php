<?php
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
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         's&yac{ _7{ObIdup-~77}&Un42gjZ@( E8%=IA:RCUY}i_h($vW}^bM}OnMOXsfj' );
define( 'SECURE_AUTH_KEY',  's MG?qrvq_&I5$KQ2_Nu:sNuxv^R`?_rj>35}PE<;y|<8x*g][aQ_cq=U<BS*F7i' );
define( 'LOGGED_IN_KEY',    'AM!WdB,V#5{#XSt_ZizH2/i(4cp;5O.z{$%!WY|N/.f)s-J3f[9/)9gXl4;xOSpC' );
define( 'NONCE_KEY',        'Ag0tx$ObQF9ObB=t$>V32]|^v3ev68HW%{XYB$8J.LbCc>svMASE-/m4Z~??|t<)' );
define( 'AUTH_SALT',        'gw7`l$qKAe?2U|I TIU{[^=^FLJ^p>HbsfxY&l.LX_6m@vi$j{6{dwi,zCa1oGTs' );
define( 'SECURE_AUTH_SALT', 'T[FHV?N0]pr4{a5Hg76U-Pl{tH:3@3@ap>k| Z1}:tN`Zb {-&A,X5Hc(G262},j' );
define( 'LOGGED_IN_SALT',   'T2#lRayc]F |1Bobp`+_0Yr?wRRq uW#{89{tOxCR> OsWH|X2{~Kdr)0z1p<Xsj' );
define( 'NONCE_SALT',       'nNB3/*_e*_~P#vHDbd-<:D8fkZ=W$;G#6vDuBC5Sp~T^,Zt H-QH_G~u]6wBgM)P' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );


define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
define( 'DOMAIN_CURRENT_SITE', 'localhost' );
define( 'PATH_CURRENT_SITE', '/wordpress/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );


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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
