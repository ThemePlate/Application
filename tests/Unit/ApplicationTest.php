<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ThemePlate\Application;
use ThemePlate\Application\Constants;

class ApplicationTest extends TestCase {
	use Constants;

	public static function setUpBeforeClass(): void {
		( new Application() )->boot();
	}

	public function test_default_constant_values_loaded_correctly_even_without_dotenv_file(): void {
		$constants = $this->get_opinionated_constants();

		if ( ! defined( 'WP_TESTS_TABLE_PREFIX' ) ) {
			$constants += $this->get_custom_constants( __DIR__ );
		}

		foreach ( $constants as $name => $default ) {
			$value = is_array( $default ) ? $default[0] : $default;

			// "Constant '$name' defaults to '$value'";
			$this->assertSame( $value, constant( $name ) );
		}
	}
}
