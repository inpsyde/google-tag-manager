<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer\User;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\User
 */
class UserDataCollector implements DataCollectorInterface {

	const VISITOR_ROLE = 'visitor';

	const SETTING__KEY = 'userData';

	const SETTING__ENABLED = 'enabled';
	const SETTING__VISITOR_ROLE = 'visitor_role';
	const SETTING__FIELDS = 'fields';

	/**
	 * @var array
	 */
	private $settings = [
		self::SETTING__ENABLED      => DataCollectorInterface::VALUE_DISABLED,
		self::SETTING__VISITOR_ROLE => self::VISITOR_ROLE,
		self::SETTING__FIELDS       => [],
	];

	/**
	 * SiteInfo constructor.
	 *
	 * @param SettingsRepository $repository
	 */
	public function __construct( SettingsRepository $repository ) {

		$settings       = $repository->get_option( self::SETTING__KEY );
		$this->settings = array_replace_recursive( $this->settings, array_filter( $settings ) );
	}

	/**
	 * @return bool
	 */
	public function enabled(): bool {

		return $this->settings[ self::SETTING__ENABLED ] === DataCollectorInterface::VALUE_ENABLED;
	}

	/**
	 * @return string
	 */
	public function visitor_role(): string {

		return $this->settings[ self::SETTING__VISITOR_ROLE ];
	}

	/**
	 * @return array
	 */
	public function fields(): array {

		return $this->settings[ self::SETTING__FIELDS ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function data(): array {

		$current_user = wp_get_current_user();
		$is_logged_in = is_user_logged_in();

		$data = [];
		foreach ( $this->fields() as $field ) {
			$data[ $field ] = $current_user->{$field} ?? '';
		}

		// only change the role, if the user has marked this field in backend.
		if ( isset( $data[ 'role' ] ) ) {
			if ( ! $is_logged_in && $this->visitor_role() !== '' ) {
				$data[ 'role' ] = $this->visitor_role();
			} elseif ( $is_logged_in ) {
				$data[ 'role' ] = $current_user->roles[ 0 ];
			}
		}

		$data[ 'isLoggedIn' ] = $is_logged_in ? TRUE : FALSE;

		return [
			'user' => $data,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_allowed(): bool {

		return $this->enabled();
	}
}
