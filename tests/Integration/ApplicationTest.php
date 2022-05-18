<?php

/**
 * @package ThemePlate
 */

namespace Tests\Integration;

use Tests\Constants;
use ThemePlate\Application;
use WP_UnitTestCase;

class ApplicationTest extends WP_UnitTestCase {
	use Constants;

	public static function setUpBeforeClass(): void {
		( new Application() )->boot( self::$default_base_path );
	}

	public function test_defining_standard_constants_are_skipped_if_wordpress_already_bootstrapped(): void {
		$this->assertIsString( WPINC );

		$our_default = $this->get_standard_constants();

		// Limited to what we are getting from the testing environment
		$this->assertIsString( ABSPATH );
		$this->assertNotSame( ABSPATH, $our_default['ABSPATH'] );
		$this->assertNotSame( WP_CONTENT_DIR, $our_default['WP_CONTENT_DIR'] );
		$this->assertNotSame( WP_CONTENT_URL, $our_default['WP_CONTENT_URL'] );
		$this->assertNotSame( WP_DEFAULT_THEME, $our_default['WP_DEFAULT_THEME'] );
	}
}
