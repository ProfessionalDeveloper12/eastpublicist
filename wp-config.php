<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp869' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '0qkiyqu8oqzo8g2ymonnesl90crsi4djjbtn2qknigthzr1m1tjge1xzqzwis7xy' );
define( 'SECURE_AUTH_KEY',  'o5aon7vkysvzdnx2fapsovltzls7o4svvnalk15qubcowl9dqxjx4t1lt3rgfiwh' );
define( 'LOGGED_IN_KEY',    '3bbvntwbrpqzam8c7xpcrwumkk6kgbd2mlmhl7lfynyugofdunuohqowv00jl4y1' );
define( 'NONCE_KEY',        'gafu6n7zlvwgebq1glihsmcs36avkbeopbv0f1tpbj2xorqwqo3mgrjjgt3rabie' );
define( 'AUTH_SALT',        's9zxcq9mljahbxtnh3bp3u9j6kp03ilnufkvsfwcnxqribfhvc4sbvflcirssoeh' );
define( 'SECURE_AUTH_SALT', 'dspqr0sqwxwvscxvmrlvmg5qumd4nutxwbisogcmme6ia8e1brura3y0eahq82ue' );
define( 'LOGGED_IN_SALT',   'tvrlhcz8pa7ibgwocawdamtsc8a18jxspgrw0idqetnclvji7khp2jr4czusxec9' );
define( 'NONCE_SALT',       '4vp5i9f43km1wckfltutreniyc9fjyneyry4amci9ask4v9d8hiojhploqjohsug' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpoc_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
