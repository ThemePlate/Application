<?php

/**
 * For building a modern WordPress site
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate\Application;

use Dotenv\Dotenv;
use Env\Env;
use Error;

class Core {

	use Constants;


	public static function setup( string $env_path ): void {

		if ( defined( 'WPINC' ) ) {
			throw new Error( 'WordPress is already loaded.' );
		}

		if ( '' === $env_path ) {
			throw new Error( 'Environment path must be specified.' );
		}

		Dotenv::createImmutable( $env_path )->safeLoad();

		Env::$options |= Env::USE_ENV_ARRAY;

		new self( $env_path );

	}


	protected function __construct( string $base_path ) {

		// Loop all must-haves with our defaults
		foreach ( $this->get_opinionated_constants() as $constant => $default ) {
			$this->define( $constant, $default );
		}

		$public_path = $this->join_path_parts( $base_path, PUBLIC_ROOT );
		$wp_core_dir = $this->join_path_parts( $public_path, WP_ROOT_DIR );

		if ( ! file_exists( $wp_core_dir . '/wp-blog-header.php' ) ) {
			throw new Error( 'Specified path is not a valid WordPress installation.' );
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

		/** With Cloudflare Flexible SSL */
		if ( isset( $_SERVER['HTTP_CF_VISITOR'] ) && '{"scheme":"https"}' === $_SERVER['HTTP_CF_VISITOR'] ) {
			$_SERVER['HTTPS'] = 'on';
		}

		// phpcs:ignore WordPress.PHP.IniSet.display_errors_Blacklisted
		ini_set( 'display_errors', '0' );

		/**
		 * Custom Settings
		 */
		foreach ( $this->get_custom_constants( $public_path ) as $constant => $default ) {
			$this->define( $constant, $default );
		}

		$local_config = $base_path . '/wp-config-local.php';

		if ( file_exists( $local_config ) ) {
			require_once $local_config;
		}

	}

}
