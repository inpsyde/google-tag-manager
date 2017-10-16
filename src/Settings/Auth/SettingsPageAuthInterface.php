<?php declare( strict_types=1 );

namespace Inpsyde\GoogleTagManager\Settings\Auth;

use Brain\Nonces\NonceInterface;

/**
 * Interface SettingsPageAuthInterface
 *
 * @package Inpsyde\GoogleTagManager\Settings
 */
interface SettingsPageAuthInterface {

	/**
	 * @param array $request_data
	 *
	 * @return bool
	 */
	public function is_allowed( array $request_data = [] ): bool;

	/**
	 * @return NonceInterface
	 */
	public function nonce(): NonceInterface;

	/**
	 * @return string
	 */
	public function cap(): string;
}
