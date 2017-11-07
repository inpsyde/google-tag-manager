<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings\Auth;

use Brain\Nonces\ArrayContext;
use Brain\Nonces\NonceInterface;
use Brain\Nonces\WpNonce;
use const Inpsyde\GoogleTagManager\ACTION_DEBUG;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
class SettingsPageAuth implements SettingsPageAuthInterface {

	const DEFAULT_CAP = 'manage_options';

	/**
	 * @var string
	 */
	private $cap;

	/**
	 * @var WpNonce
	 */
	private $nonce;

	/**
	 * SettingsPageAuth constructor.
	 *
	 * @param string         $action
	 * @param string         $cap
	 * @param NonceInterface $nonce
	 */
	public function __construct( string $action, $cap = NULL, NonceInterface $nonce = NULL ) {

		$this->cap   = $cap ?? self::DEFAULT_CAP;
		$this->nonce = $nonce ?? new WpNonce( $action );
	}

	/**
	 * @param array $request_data
	 *
	 * @return bool
	 */
	public function is_allowed( array $request_data = [] ): bool {

		if ( ! current_user_can( $this->cap ) ) {

			do_action(
				'inpsyde-google-tag-manager.error',
				'User has no sufficient rights to save page',
				[
					'method' => __METHOD__,
					'cap'    => $this->cap,
					'nonce'  => $this->nonce
				]
			);

			return FALSE;
		}

		if ( is_multisite() && ms_is_switched() ) {

			return FALSE;
		}

		return $this->nonce->validate( new ArrayContext( $request_data ) );
	}

	/**
	 * @return NonceInterface
	 */
	public function nonce(): NonceInterface {

		return $this->nonce;
	}

	/**
	 * @return string
	 */
	public function cap(): string {

		return $this->cap;
	}

}