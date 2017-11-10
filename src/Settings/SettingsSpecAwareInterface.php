<?php

namespace Inpsyde\GoogleTagManager\Settings;

/**
 * Interface SettingsAwareInterface
 *
 * @package Inpsyde\GoogleTagManager\Settings
 */
interface SettingsSpecAwareInterface {

	/**
	 * Returns an array containing the fields specification and optionally validators and filters.
	 *
	 * @return array
	 */
	public function settings_spec(): array;

}
