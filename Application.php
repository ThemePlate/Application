<?php

/**
 * For building a modern WordPress site
 *
 * @package ThemePlate
 * @since   0.1.0
 */

namespace ThemePlate;

use League\Container\Container;

class Application extends Container {

	protected static self $instance;


	public static function instance(): Application {

		_deprecated_function( __METHOD__, '2.0.0' );

		return self::$instance;

	}


	public function boot(): Application {

		_deprecated_function( __METHOD__, '2.0.0' );

		self::$instance = $this;

		return $this;

	}

}
