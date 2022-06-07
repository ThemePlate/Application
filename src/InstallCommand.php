<?php

/**
 * @package ThemePlate
 */

namespace ThemePlate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class InstallCommand extends Command {

	use Constants;


	// phpcs:disable WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	protected static $defaultName        = 'install';
	protected static $defaultDescription = 'Install WordPress';
	// phpcs:enable WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase


	protected function configure(): void {

		$this->addArgument( 'path', InputArgument::OPTIONAL, 'Specify the install path', $this->get_install_path() );
		$this->addOption( 'tag', null, InputOption::VALUE_OPTIONAL, 'Version tag to install', 'latest' );

	}


	protected function execute( InputInterface $input, OutputInterface $output ): int {

		$args = array(
			'sh',
			dirname( __FILE__, 2 ) . '/bin/install-wp.sh',
			$input->getArgument( 'path' ),
			$input->getOption( 'tag' ),
		);

		$process = new Process( $args );

		$process->run(
			function ( $type, $buffer ) {
				echo $buffer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		);

		return Command::SUCCESS;

	}


	protected function get_install_path(): string {

		$default  = ltrim( self::$default_wp_root_dir, '/\\' );
		$composer = getcwd() . '/composer.json';

		if ( ! file_exists( $composer ) ) {
			return $default;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$contents = file_get_contents( $composer );

		if ( false === $contents ) {
			return $default;
		}

		$decoded = json_decode( $contents, true );

		if ( ! $decoded ) {
			return $default;
		}

		return $decoded['extra']['wordpress-install-dir'] ?? $default;

	}

}
