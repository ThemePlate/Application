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

		return self::$instance;

	}


	public function boot(): Application {

		self::$instance = $this;

		return $this;

	}

}
