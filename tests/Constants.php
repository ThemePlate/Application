<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use ThemePlate\Constants as BaseConstants;

trait Constants {
	use BaseConstants;

	public static string $default_wp_home = 'http://localhost';

	public function get_test_constants(): array {
		return array(
			'WP_CONTENT_DIR'   => self::$default_base_path . self::$default_content_dir,
			'WP_CONTENT_URL'   => self::$default_wp_home . self::$default_content_dir,
			'WP_DEFAULT_THEME' => self::$default_wp_theme,
			'ABSPATH'          => self::$default_base_path . self::$default_wp_root_dir,
		);
	}
}
