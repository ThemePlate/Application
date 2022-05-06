<?php

/**
 * For building a modern WordPress site
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate;

use Dotenv\Dotenv;
use Env\Env;

class Application {

	public function __construct( string $base_path ) {

		$dotenv = Dotenv::createImmutable( $base_path );

		$dotenv->load();
		$dotenv->required( 'WP_HOME' );

		Env::$options |= Env::USE_ENV_ARRAY;

		// Database Settings
		define( 'DB_NAME', Env::get( 'DB_NAME' ) ?: 'local' );
		define( 'DB_USER', Env::get( 'DB_USER' ) ?: 'root' );
		define( 'DB_PASSWORD', Env::get( 'DB_PASSWORD' ) ?: 'root' );
		define( 'DB_HOST', Env::get( 'DB_HOST' ) ?: 'localhost' );
		define( 'DB_CHARSET', Env::get( 'DB_CHARSET' ) ?: 'utf8' );
		define( 'DB_COLLATE', Env::get( 'DB_COLLATE' ) ?: '' );

		// Authentication Unique Keys and Salts
		define( 'AUTH_KEY', Env::get( 'AUTH_KEY' ) );
		define( 'SECURE_AUTH_KEY', Env::get( 'SECURE_AUTH_KEY' ) );
		define( 'LOGGED_IN_KEY', Env::get( 'LOGGED_IN_KEY' ) );
		define( 'NONCE_KEY', Env::get( 'NONCE_KEY' ) );
		define( 'AUTH_SALT', Env::get( 'AUTH_SALT' ) );
		define( 'SECURE_AUTH_SALT', Env::get( 'SECURE_AUTH_SALT' ) );
		define( 'LOGGED_IN_SALT', Env::get( 'LOGGED_IN_SALT' ) );
		define( 'NONCE_SALT', Env::get( 'NONCE_SALT' ) );

		// Debugging Settings
		define( 'WP_DEBUG', Env::get( 'WP_DEBUG' ) ?: false );
		define( 'WP_DEBUG_LOG', Env::get( 'WP_DEBUG_LOG' ) ?? true );
		define( 'WP_DEBUG_DISPLAY', false );
		define( 'SCRIPT_DEBUG', false );

		/**
		 * Custom Settings
		 */
		define( 'WP_HOME', Env::get( 'WP_HOME' ) );

		// Opinionated defaults
		define( 'WP_SITEURL', WP_HOME . '/wp' );
		define( 'CONTENT_DIR', '/content' );
		define( 'WP_CONTENT_DIR', $base_path . CONTENT_DIR );
		define( 'WP_CONTENT_URL', WP_HOME . CONTENT_DIR );

		define( 'WP_ENVIRONMENT_TYPE', Env::get( 'WP_ENVIRONMENT_TYPE' ) ?: 'local' );
		define( 'WP_DEFAULT_THEME', Env::get( 'WP_DEFAULT_THEME' ) ?? '' );

		define( 'AUTOMATIC_UPDATER_DISABLED', true );
		define( 'DISABLE_WP_CRON', Env::get( 'DISABLE_WP_CRON' ) ?: false );
		define( 'DISALLOW_FILE_EDIT', true );
		define( 'DISALLOW_FILE_MODS', true );

		/**
		 * Behind load balancers or reverse proxies that support HTTP_X_FORWARDED_PROTO
		 * See https://developer.wordpress.org/reference/functions/is_ssl
		 */
		if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
			$_SERVER['HTTPS'] = 'on';
		}

		/** Absolute path to the WordPress directory. */
		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', $base_path . '/wp' );
		}

	}

}
