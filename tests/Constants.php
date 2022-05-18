<?php

/**
 * @package ThemePlate
 */

namespace Tests;

trait Constants {
	public static string $default_base_path   = __DIR__;
	public static string $default_wp_home     = 'http://localhost';
	public static string $default_wp_root_dir = '/wp';
	public static string $default_content_dir = '/content';
	public static string $default_wp_env_type = 'local';

	public function get_standard_constants(): array {
		return array(
			'DB_NAME'                    => 'local',
			'DB_USER'                    => 'root',
			'DB_PASSWORD'                => 'root',
			'DB_HOST'                    => 'localhost',
			'DB_CHARSET'                 => 'utf8',
			'DB_COLLATE'                 => '',
			'WP_DEBUG'                   => false,
			'WP_DEBUG_LOG'               => true,
			'WP_DEBUG_DISPLAY'           => false,
			'SCRIPT_DEBUG'               => false,
			'WP_HOME'                    => self::$default_wp_home,
			'WP_ROOT_DIR'                => self::$default_wp_root_dir,
			'CONTENT_DIR'                => self::$default_content_dir,
			'WP_SITEURL'                 => self::$default_wp_home . self::$default_wp_root_dir,
			'WP_CONTENT_DIR'             => self::$default_base_path . self::$default_content_dir,
			'WP_CONTENT_URL'             => self::$default_wp_home . self::$default_content_dir,
			'WP_ENVIRONMENT_TYPE'        => self::$default_wp_env_type,
			'WP_DEFAULT_THEME'           => '',
			'AUTOMATIC_UPDATER_DISABLED' => true,
			'DISABLE_WP_CRON'            => false,
			'DISALLOW_FILE_EDIT'         => true,
			'DISALLOW_FILE_MODS'         => false,
			'ABSPATH'                    => self::$default_base_path . self::$default_wp_root_dir,
		);
	}
}
