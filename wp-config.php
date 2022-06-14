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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'assignment' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'dxX&KJ1W&d0(LsvTDv7:UY*FFs:3;xqHF}(KF9l8rfXg(mmoa?;%{+;VG5OUGPCd' );
define( 'SECURE_AUTH_KEY',  '3-M c!#w;TqSeCBY=Cv!?8f+;kD348#x$f)K~Rdzm]lROtTGZ-P&564lcT#BClLk' );
define( 'LOGGED_IN_KEY',    'Z<[K&n:wzgvnC:J-OAOnj:tM(M~pA  ?sL*Z5iz[-_Tp3@xG7E!mI!7wRAi&]:H]' );
define( 'NONCE_KEY',        '7%.DKSZYIMS`O3LEt]I!VRV~GC7XbA8Wni9Av>7vv#S/T,DA{!=zkv}S1*%u8yv0' );
define( 'AUTH_SALT',        'Mx-jT%z=.{rNayj5(JW*JH1Y/zXq$k JFYgMcy(hw{}W7Jq%UdslYF(%b<iL!#BG' );
define( 'SECURE_AUTH_SALT', 'P1tM-U{Z{cm?`%kt_ X/<~_;6RLKFnh0F1+ZT$7_ LStPK_T|O>/MI$|s4%5Sg)$' );
define( 'LOGGED_IN_SALT',   '?{Y&>J]_ez?F969drBw$$8#od1I=@=Bn@v!v)P?H{q++-)-44nM684=Bqmo^4Dk&' );
define( 'NONCE_SALT',       'OOr!a`DV2X$VtIZGB=L``0>C4$AJ1D,n$yOPN|/xGss(:vB+g2oqbN^py$uY$vs2' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
