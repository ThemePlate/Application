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

	// phpcs:disable WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	protected static $defaultName        = 'install';
	protected static $defaultDescription = 'Install WordPress';
	// phpcs:enable WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase


	protected function configure(): void {

		$this->addArgument( 'path', InputArgument::OPTIONAL, 'Specify the install path', './wordpress' );
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

}
