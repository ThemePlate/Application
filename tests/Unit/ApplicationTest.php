<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Constants;
use ThemePlate\Application;

class ApplicationTest extends TestCase {
	use Constants;

	public static function setUpBeforeClass(): void {
		( new Application() )->boot( self::$default_base_path );
	}

	public function test_default_constant_values_loaded_correctly_even_without_dotenv_file(): void {
		foreach ( $this->get_standard_constants() as $name => $value ) {
			$this->assertSame( constant( $name ), $value );
		}
	}
}
