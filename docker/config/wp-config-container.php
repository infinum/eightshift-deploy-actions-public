<?php
// Helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	function getenv_docker($env, $default = '')
	{
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		} else if (($val = getenv($env)) !== false) {
			return $val;
		} else {
			return $default;
		}
	}
}

/* -------------------------------------------------------------- */
/* Database settings */
/* -------------------------------------------------------------- */

// Database name.
define('DB_NAME', getenv_docker('WORDPRESS_DB_NAME'));

// Database username.
define('DB_USER', getenv_docker('WORDPRESS_DB_USER'));

// Database password.
define('DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD'));

// Database hostname.
define('DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql'));

// Database charset to use in creating database tables.
define('DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8mb4'));

// The database collate type. Don't change this if in doubt.
define('DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE'));

// Table prefix.
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

/* -------------------------------------------------------------- */
/* Authentication unique keys and salts */
/* https://api.wordpress.org/secret-key/1.1/salt/ */
/* -------------------------------------------------------------- */

define('AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY'));
define('SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY'));
define('NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY'));
define('AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT'));
define('NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT'));

/* -------------------------------------------------------------- */
/* Standard constants */
/* -------------------------------------------------------------- */

// Enviroment settings.
define('WP_ENVIRONMENT_TYPE', getenv_docker('WP_ENVIRONMENT_TYPE'));

// This must be done before any other constants are defined.
$constantsPath = __DIR__ . \DIRECTORY_SEPARATOR . 'eightshift/constants.php';
$constants = file_exists($constantsPath) ? require_once($constantsPath) : "";

// Optimisation constants if not set by Utils plugin.
if (!defined('COMPRESS_CSS')) {
	\define('COMPRESS_CSS', true);
}
if (!defined('COMPRESS_SCRIPTS')) {
	\define('COMPRESS_SCRIPTS', true);
}
if (!defined('CONCATENATE_SCRIPTS')) {
	\define('CONCATENATE_SCRIPTS', true);
}
if (!defined('ENFORCE_GZIP')) {
	\define('ENFORCE_GZIP', true);
}
if (!defined('DISALLOW_FILE_EDIT')) {
	\define('DISALLOW_FILE_EDIT', true);
}
if (!defined('DISALLOW_FILE_MODS')) {
	\define('DISALLOW_FILE_MODS', true);
}
if (!defined('AUTOMATIC_UPDATER_DISABLED')) {
	\define('AUTOMATIC_UPDATER_DISABLED', true);
}
if (!defined('FORCE_SSL_LOGIN')) {
	\define('FORCE_SSL_LOGIN', true);
}
if (!defined('FORCE_SSL_ADMIN')) {
	\define('FORCE_SSL_ADMIN', true);
}
if (!defined('SCRIPT_DEBUG')) {
	\define('SCRIPT_DEBUG', false);
}
if (!defined('WP_DEBUG')) {
	\define('WP_DEBUG', false);
}
if (!defined('WP_DEBUG_LOG')) {
	\define('WP_DEBUG_LOG', false);
}
if (!defined('WP_DEBUG_DISPLAY')) {
	\define('WP_DEBUG_DISPLAY', false);
}
if (!defined('AUTOSAVE_INTERVAL')) {
	\define('AUTOSAVE_INTERVAL', 240);
}
if (!defined('WP_POST_REVISIONS')) {
	define('WP_POST_REVISIONS', 20);
}

// Cron job is used from the AWS.
define('DISABLE_WP_CRON', false);
define('ALTERNATE_WP_CRON', true);

# MySQL.
define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);

/* -------------------------------------------------------------- */
/* Project settings */
/* -------------------------------------------------------------- */

// Set cookie domain.
if (!defined('WP_CLI') && isset($_SERVER['HTTP_HOST'])) {
	define('COOKIE_DOMAIN', $_SERVER['HTTP_HOST']);
}

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact.
if (!empty($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) && $_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'] === 'https') {
	$_SERVER['HTTPS'] = 'on';
}

// Bugsnag.
define('BUGSNAG_API_KEY', getenv_docker('BUGSNAG_API_KEY'));

// GTM.
define('GTM_ID', getenv_docker('GTM_ID'));

/* -------------------------------------------------------------- */
/* Plugins settings */
/* -------------------------------------------------------------- */

// WP Mail SMTP plugin.
define('WPMS_MAILER', 'mailgun');
define('WPMS_ON', true);
define('WPMS_MAILGUN_API_KEY', getenv_docker('WPMS_MAILGUN_API_KEY'));
define('WPMS_MAILGUN_DOMAIN', getenv_docker('WPMS_MAILGUN_DOMAIN'));
define('WPMS_MAILGUN_REGION', getenv_docker('WPMS_MAILGUN_REGION'));

// S3 Uploads plugin.
define('S3_UPLOADS_OBJECT_ACL', getenv_docker('S3_UPLOADS_OBJECT_ACL', 'private'));
define('S3_UPLOADS_USE_INSTANCE_PROFILE', getenv_docker('S3_UPLOADS_USE_INSTANCE_PROFILE', true));
define('S3_UPLOADS_BUCKET', getenv_docker('S3_UPLOADS_BUCKET'));
define('S3_UPLOADS_BUCKET_URL', getenv_docker('S3_UPLOADS_BUCKET_URL'));
define('S3_UPLOADS_REGION', getenv_docker('S3_UPLOADS_REGION'));

// ES Utils plugin.
define('ES_UTILS_CLOUDFRONT_DISTRIBUTION_ID', getenv_docker('ES_UTILS_CLOUDFRONT_DISTRIBUTION_ID'));
define('ES_UTILS_CLOUDFRONT_DISTRIBUTION_REGION', getenv_docker('ES_UTILS_CLOUDFRONT_DISTRIBUTION_REGION'));

/* -------------------------------------------------------------- */
/* WordPress initialization */
/* -------------------------------------------------------------- */

// Absolute path to the WordPress directory.
if (! defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

// Sets up WordPress vars and included files.
require_once ABSPATH . 'wp-settings.php';
