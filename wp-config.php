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
define( 'DB_NAME', 'sneaker_database' );

/** Database username */
define( 'DB_USER', 'admin' );

/** Database password */
define( 'DB_PASSWORD', '12345678' );

/** Database hostname */
define( 'DB_HOST', 'sneaker-database.c0y0nzv8ci59.ap-south-1.rds.amazonaws.com' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         'mciqkgor6zj09p8frs7oecqljetvdx49ngnm0ynpr41w9249xlzxugapimosrwcf' );
define( 'SECURE_AUTH_KEY',  'xbcsr7gk1niqgntklnvacuekuqk45gwx40aiyzzd6tybdo5ujmxhpsbuvzacfi88' );
define( 'LOGGED_IN_KEY',    'syb7cvtmef67z2ur2ygxelskneql6zyqwe90huhwj9r6w8q6drvxj0edffxm6tqk' );
define( 'NONCE_KEY',        'nguevsyrhidyilkfdo5kufdmm3dvnr35hubiveex1we1mqqbnu5k2odi9kiupyjy' );
define( 'AUTH_SALT',        '1ocjeasnzy41smhwcbsawdrycfhhbedzphx9c8w0altivjzgvhpdidcj4obkiekx' );
define( 'SECURE_AUTH_SALT', 'qcnoldakrdp8viq2kn1alr55r1ijiz8vpk7dm9u0frhv9by8lm1uuvpgmkqngs9q' );
define( 'LOGGED_IN_SALT',   'zlbwtgpx18w9wemszmkoyfghvhyhdc4vuty0g9gimqxxlgcsq3iwvak7oomf8rfi' );
define( 'NONCE_SALT',       'oaihxxdoiekimr44bgrvwe7bjcz0c2wkchskshxuj6z1ghnpde2didykza6tkrsu' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp3b_';

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


set_time_limit(300);
