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
define('DB_NAME', 'naval_college_2017');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'cylL1YJY!lA7!EnsqH3rHUP==S@YP)DA3pm-3i@<Ds,l&~{uu/C~So6r.OQ)/ef`');
define('SECURE_AUTH_KEY',  '3GIzaU!ra BTt+npU6hlP|:frGI)5%&k0Sg$EhbB$BstFTM/dA#6iq%vSm~%>9 2');
define('LOGGED_IN_KEY',    'F._2H>/,MVUhDbH2*PAS^]s4_I|E#o&<~{J=6Dy!}G |;|mObt3$nrxc.3JkU(~9');
define('NONCE_KEY',        '>T>2.$fbhRVr #e59SpJ6`GV^LSH!Kgi:D2#mZa`J3*_<c)5C[lMvY}Iat[@W.5W');
define('AUTH_SALT',        'Y:Y@R~WvDC>.y`skt-&TPr4xY(>[6N85Fl~AKop{CHrztCJ^B]vS7]MfekC*QPgK');
define('SECURE_AUTH_SALT', 'd)SrYyfMvQ<ntrtJMbLsW6HKc6dXT~pB1U#L@nP }WJlr=.4w~~s,9/},+HlGb;|');
define('LOGGED_IN_SALT',   ',UE-,mE?~L{x=Ncq14gxpX9H2fnr9A]|0E_%#EXR^jNH0Wu6b%$(j?Ub<!m-c>YQ');
define('NONCE_SALT',       'g`5)+xF-p5diz-5N~rt[dSTZ^*L56wUsL9s[a1VvM</|du/STBOc5Hk6EV#5 6/9');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
