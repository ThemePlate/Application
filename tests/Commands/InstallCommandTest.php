<?php

/**
 * @package ThemePlate
 */

namespace Tests\Commands;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use ThemePlate\InstallCommand;
use PHPUnit\Framework\TestCase;

class InstallCommandTest extends TestCase {
	public function testExecute(): void {
		$command = new InstallCommand();
		$tester  = new CommandTester( $command );

		( new Application() )->add( $command );
		ob_start();
		$tester->execute( array() );
		$this->assertIsString( ob_get_clean() );
		$tester->assertCommandIsSuccessful();
	}
}
