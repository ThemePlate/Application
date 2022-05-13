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
use League\Container\Container;

class Application extends Container {

	public function boot( string $base_path ): Application {

		$dotenv = Dotenv::createImmutable( $base_path );

		$dotenv->safeLoad();

		Env::$options |= Env::USE_ENV_ARRAY;

		if ( ! defined( 'WPINC' ) ) {
			$this->bootstrap_wordpress( $base_path );
		}

		return $this;

	}


	protected function bootstrap_wordpress( string $base_path ): void {

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
		define( 'WP_DEBUG_DISPLAY', Env::get( 'WP_DEBUG_DISPLAY' ) ?: false );
		define( 'SCRIPT_DEBUG', Env::get( 'SCRIPT_DEBUG' ) ?: false );

		/**
		 * Behind load balancers or reverse proxies that support HTTP_X_FORWARDED_PROTO
		 * See https://developer.wordpress.org/reference/functions/is_ssl
		 */
		if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
			$_SERVER['HTTPS'] = 'on';
		}

		/**
		 * Custom Settings
		 */
		define( 'WP_HOME', Env::get( 'WP_HOME' ) ?? $this->targeted_request() );

		// Opinionated defaults
		define( 'WP_ROOT_DIR', Env::get( 'WP_ROOT_DIR' ) ?? '/wp' );
		define( 'CONTENT_DIR', Env::get( 'CONTENT_DIR' ) ?? '/content' );

		define( 'WP_SITEURL', Env::get( 'WP_SITEURL' ) ?: WP_HOME . WP_ROOT_DIR );
		define( 'WP_CONTENT_DIR', Env::get( 'WP_CONTENT_DIR' ) ?: $base_path . CONTENT_DIR );
		define( 'WP_CONTENT_URL', Env::get( 'WP_CONTENT_URL' ) ?: WP_HOME . CONTENT_DIR );

		define( 'WP_ENVIRONMENT_TYPE', Env::get( 'WP_ENVIRONMENT_TYPE' ) ?: 'local' );
		define( 'WP_DEFAULT_THEME', Env::get( 'WP_DEFAULT_THEME' ) ?? '' );

		define( 'AUTOMATIC_UPDATER_DISABLED', Env::get( 'AUTOMATIC_UPDATER_DISABLED' ) ?: true );
		define( 'DISABLE_WP_CRON', Env::get( 'DISABLE_WP_CRON' ) ?: false );
		define( 'DISALLOW_FILE_EDIT', Env::get( 'DISALLOW_FILE_EDIT' ) ?: true );
		define( 'DISALLOW_FILE_MODS', Env::get( 'DISALLOW_FILE_MODS' ) ?: true );

		$local_config = $base_path . '/wp-config-local.php';

		if ( file_exists( $local_config ) ) {
			require_once $local_config;
		}

		/** Absolute path to the WordPress directory. */
		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', $base_path . WP_ROOT_DIR );
		}

	}


	protected function targeted_request(): string {

		$protocol = 'http';

		if ( isset( $_SERVER['HTTPS'] ) && in_array( strtolower( $_SERVER['HTTPS'] ), array( '1', 'on' ), true ) ) {
			$protocol .= 's';
		} elseif ( isset( $_SERVER['SERVER_PORT'] ) && ( '443' === $_SERVER['SERVER_PORT'] ) ) {
			$protocol .= 's';
		}

		return $protocol . '://' . $_SERVER['HTTP_HOST'];

	}

}
