<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use ThemePlate\Application\Constants as BaseConstants;

trait Constants {
	use BaseConstants;

	public function get_test_constants(): array {
		return array(
			'WP_CONTENT_DIR'   => self::DEFAULT['BASE_PATH'] . self::DEFAULT['CONTENT_DIR'],
			'WP_CONTENT_URL'   => 'http://localhost/' . self::DEFAULT['CONTENT_DIR'],
			'WP_DEFAULT_THEME' => self::DEFAULT['WP_THEME'],
			'ABSPATH'          => self::DEFAULT['BASE_PATH'] . self::DEFAULT['WP_ROOT_DIR'],
		);
	}
}
