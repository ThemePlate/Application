<?php

/**
 * @package ThemePlate
 */

namespace Tests\Integration;

use ThemePlate\Application;
use ThemePlate\Application\Constants;
use WP_UnitTestCase;

class ApplicationTest extends WP_UnitTestCase {
	use Constants;

	public static function setUpBeforeClass(): void {
		( new Application() )->boot();
	}

	public function test_defining_standard_constants_are_skipped_if_wordpress_already_bootstrapped(): void {
		$this->assertIsString( WPINC );

		$our_default = array(
			'WP_CONTENT_DIR'   => __DIR__ . self::DEFAULT_VALUE['CONTENT_DIR'],
			'WP_CONTENT_URL'   => 'http://localhost/' . self::DEFAULT_VALUE['CONTENT_DIR'],
			'WP_DEFAULT_THEME' => self::DEFAULT_VALUE['THEME_SLUG'],
			'ABSPATH'          => __DIR__ . self::DEFAULT_VALUE['WP_CORE_DIR'],
		);

		// Limited to what we are getting from the testing environment
		$this->assertIsString( ABSPATH );
		$this->assertNotSame( ABSPATH, $our_default['ABSPATH'] );
		$this->assertNotSame( WP_CONTENT_DIR, $our_default['WP_CONTENT_DIR'] );
		$this->assertNotSame( WP_CONTENT_URL, $our_default['WP_CONTENT_URL'] );
		$this->assertNotSame( WP_DEFAULT_THEME, $our_default['WP_DEFAULT_THEME'] );
	}
}
