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

	use Constants;


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

		// Loop all must-haves with our defaults
		foreach ( $this->get_opinionated_constants() as $constant => $default ) {
			$this->define( $constant, $default );
		}

		// Authentication Unique Keys and Salts
		define( 'AUTH_KEY', Env::get( 'AUTH_KEY' ) );
		define( 'SECURE_AUTH_KEY', Env::get( 'SECURE_AUTH_KEY' ) );
		define( 'LOGGED_IN_KEY', Env::get( 'LOGGED_IN_KEY' ) );
		define( 'NONCE_KEY', Env::get( 'NONCE_KEY' ) );
		define( 'AUTH_SALT', Env::get( 'AUTH_SALT' ) );
		define( 'SECURE_AUTH_SALT', Env::get( 'SECURE_AUTH_SALT' ) );
		define( 'LOGGED_IN_SALT', Env::get( 'LOGGED_IN_SALT' ) );
		define( 'NONCE_SALT', Env::get( 'NONCE_SALT' ) );

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
		foreach ( $this->get_custom_constants( $base_path ) as $constant => $default ) {
			$this->define( $constant, $default );
		}

		$local_config = $base_path . '/wp-config-local.php';

		if ( file_exists( $local_config ) ) {
			require_once $local_config;
		}

		/** Absolute path to the WordPress directory. */
		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', $base_path . WP_ROOT_DIR );
		}

	}

}
