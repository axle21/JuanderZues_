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

define('DB_NAME', 'juanderi_WPXZP');


/** MySQL database username */

define('DB_USER', 'root');


/** MySQL database password */

define('DB_PASSWORD', 'Password@123');


/** MySQL hostname */

define('DB_HOST', 'db:3306');



/** Database Charset to use in creating database tables. */

define('DB_CHARSET', 'utf8');



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

define('AUTH_KEY', '656f7dc6a367cafd2eebe03b9a33dbcad0521015cc08a260f6733bc58cd77ac4');
define('SECURE_AUTH_KEY', '97bd5cf93c4ca8349ea270e8097c3e00170075d2924b08ead9e052bd2e56ee3f');
define('LOGGED_IN_KEY', 'fda3153efd4e86f54609ab46d9ee48d936207b6597cc955a258e49529c82c3a0');
define('NONCE_KEY', '7a71fc2fff03d067c97c39a6dea84420297dc76dda8b321c2f1575256d6a653d');
define('AUTH_SALT', 'decad276fec48c5576468698caebd8b5a608c8ea3dabeb3c00a4b51fe7a08d80');
define('SECURE_AUTH_SALT', '94de3100531a37ba9c0fc23fef18c773bd735b270f890fcb5788ce15657c2cfe');
define('LOGGED_IN_SALT', '37cfa5c7cf434594b4c20e02da38d28f527e396653b2bb95f32faa3e2b0b7212');
define('NONCE_SALT', '8bf346c8f8b4c2949651a623dac6beb0b6019ecfc72c6ee7458cfd50fea8329d');


/**#@-*/



/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix  = '_XZP_';


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

define('WP_DEBUG', true);




// Settings modified by hosting provider
define( 'WP_CRON_LOCK_TIMEOUT', 120   );
define( 'AUTOSAVE_INTERVAL',    300   );
define( 'WP_POST_REVISIONS',    5     );
define( 'EMPTY_TRASH_DAYS',     7     );
define( 'WP_AUTO_UPDATE_CORE',  true  );
define( 'WP_HOME', 'http://localhost:8000/' );
define( 'WP_SITEURL', 'http://localhost:8000/' );
/* That's all, stop editing! Happy blogging. */


/** Absolute path to the WordPress directory. */

if ( !defined('ABSPATH') )

	define('ABSPATH', dirname(__FILE__) . '/');



/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');

