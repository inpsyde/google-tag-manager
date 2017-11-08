<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
class SettingsRepository {

	/**
	 * The name where the options are stored.
	 *
	 * @var string
	 */
	private $option_name;

	/**
	 * SettingsRegistry constructor.
	 *
	 * @param string $option_name
	 */
	public function __construct( string $option_name ) {

		$this->option_name = $option_name;
	}

	/**
	 * Returns the specific option for and "ID".
	 *
	 * @param    string $key
	 *
	 * @return   mixed
	 */
	public function get_option( $key ) {

		$options = $this->get_options();

		return isset( $options[ $key ] )
			? $options[ $key ]
			: [];
	}

	/**
	 * @param array $data
	 *
	 * @return bool
	 */
	public function update_options( array $data ): bool {

		return update_option(
			$this->option_name,
			$data
		);
	}

	/**
	 * Load all options.
	 *
	 * @return array
	 */
	public function get_options(): array {

		$options = get_option( $this->option_name, [] );

		return $options;
	}

}
