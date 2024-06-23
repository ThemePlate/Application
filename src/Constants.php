<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate\Application;

use Env\Env;

trait Constants {

	public const DEFAULT_VALUE = array(
		'PUBLIC_ROOT' => 'public',
		'WP_CORE_DIR' => 'wp',
		'CONTENT_DIR' => 'content',
		'ENVIRONMENT' => 'local',
		'THEME_SLUG'  => 'themeplate',
	);


	public readonly string $public_path;


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
			'WP_ENVIRONMENT_TYPE' => self::DEFAULT_VALUE['ENVIRONMENT'],
			'WP_DEFAULT_THEME'    => array( self::DEFAULT_VALUE['THEME_SLUG'] ),
		);

	}


	protected function join_path_parts( string ...$args ): string {

		array_walk(
			$args,
			function ( string &$part, int $key ) {
				$part = rtrim( $part, '/\\' );

				if ( $key > 0 ) {
					$part = ltrim( $part, '/\\' );
				}
			}
		);

		return implode( DIRECTORY_SEPARATOR, array_filter( $args ) );

	}


	protected function get_custom_constants( string $wp_core_dir ): array {

		$content_dir = Env::get( 'CONTENT_DIR' ) ?? self::DEFAULT_VALUE['CONTENT_DIR'];

		return array(
			'WP_SITEURL'                 => $this->join_path_parts( WP_HOME, $wp_core_dir ),
			'WP_CONTENT_DIR'             => $this->join_path_parts( $this->public_path, $content_dir ),
			'WP_CONTENT_URL'             => $this->join_path_parts( WP_HOME, $content_dir ),
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
	 * If default value is an array, the comparison operator is switched from 'null-coalescing' to 'ternary'
	 */
	protected function define( string $constant, mixed $default_value ): void {

		if ( defined( $constant ) && constant( $constant ) === $default_value ) {
			return;
		}

		if ( is_array( $default_value ) ) {
			define( $constant, Env::get( $constant ) ?? $default_value[0] );
		} else {
			// phpcs:ignore Universal.Operators.DisallowShortTernary
			define( $constant, Env::get( $constant ) ?: $default_value );
		}

	}

}
