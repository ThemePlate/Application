<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Application;

use Env\Env;

trait Constants {

	protected static string $default_base_path   = __DIR__;
	protected static string $default_public_root = 'public';
	protected static string $default_wp_root_dir = '/wp';
	protected static string $default_content_dir = '/content';
	protected static string $default_wp_env_type = 'local';
	protected static string $default_wp_theme    = 'themeplate';


	protected function get_opinionated_constants(): array {

		return array(
			'DB_NAME'             => 'local',
			'DB_USER'             => 'root',
			'DB_PASSWORD'         => 'root',
			'DB_HOST'             => '127.0.0.1',
			'DB_CHARSET'          => 'utf8',
			'DB_COLLATE'          => '',
			'WP_DEBUG'            => false,
			'WP_DEBUG_LOG'        => array( true ),
			'WP_DEBUG_DISPLAY'    => false,
			'SCRIPT_DEBUG'        => false,
			'WP_HOME'             => array( $this->targeted_request() ),
			'PUBLIC_ROOT'         => array( self::$default_public_root ),
			'WP_ROOT_DIR'         => array( self::$default_wp_root_dir ),
			'CONTENT_DIR'         => array( self::$default_content_dir ),
			'WP_ENVIRONMENT_TYPE' => self::$default_wp_env_type,
			'WP_DEFAULT_THEME'    => array( self::$default_wp_theme ),
		);

	}


	protected function get_custom_constants( string $base_path ): array {

		return array(
			'WP_SITEURL'                 => WP_HOME . WP_ROOT_DIR,
			'WP_CONTENT_DIR'             => $base_path . CONTENT_DIR,
			'WP_CONTENT_URL'             => WP_HOME . CONTENT_DIR,
			'AUTOMATIC_UPDATER_DISABLED' => true,
			'DISABLE_WP_CRON'            => false,
			'DISALLOW_FILE_EDIT'         => true,
			'DISALLOW_FILE_MODS'         => $this->is_disabled_updates(),
			'FS_METHOD'                  => array( 'direct' ),
		);

	}


	protected function targeted_request(): string {

		$protocol = 'http';

		if ( isset( $_SERVER['HTTPS'] ) && in_array( strtolower( $_SERVER['HTTPS'] ), array( '1', 'on' ), true ) ) {
			$protocol .= 's';
		} elseif ( isset( $_SERVER['SERVER_PORT'] ) && ( '443' === $_SERVER['SERVER_PORT'] ) ) {
			$protocol .= 's';
		}

		return $protocol . '://' . ( $_SERVER['HTTP_HOST'] ?? '127.0.0.1' );

	}


	public function is_disabled_updates(): bool {

		return in_array( strtolower( WP_ENVIRONMENT_TYPE ), array( 'staging', 'production' ), true );

	}


	/**
	 * @param string $constant
	 * @param mixed  $default  (boolean | string)
	 * If value is an array, the comparison operator
	 * is switched from 'null-coalescing' to 'ternary'
	 *
	 * @return void
	 */
	protected function define( string $constant, $default ): void {

		if ( defined( $constant ) && $default === constant( $constant ) ) {
			return;
		}

		if ( is_array( $default ) ) {
			define( $constant, Env::get( $constant ) ?? $default[0] );
		} else {
			// phpcs:ignore WordPress.PHP.DisallowShortTernary
			define( $constant, Env::get( $constant ) ?: $default );
		}

	}

}
