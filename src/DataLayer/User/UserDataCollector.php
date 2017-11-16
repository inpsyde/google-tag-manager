<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer\User;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\User
 */
class UserDataCollector implements DataCollectorInterface, SettingsSpecAwareInterface {

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

	/**
	 * @return array
	 */
	public function settings_spec(): array {

		$enabled = [
			'label'      => __( 'Enable/disable user data', 'inpsyde-google-tag-manager' ),
			'attributes' => [
				'name' => self::SETTING__ENABLED,
				'type' => 'select',
			],
			'choices'    => [
				DataCollectorInterface::VALUE_ENABLED  => __( 'Enabled', 'inpsyde-google-tag-manager' ),
				DataCollectorInterface::VALUE_DISABLED => __( 'Disabled', 'inpsyde-google-tag-manager' ),
			],
		];

		$visitor = [
			'label'       => __( 'Visitor role', 'inpsyde-google-tag-manager' ),
			'description' => __(
				'Which role should be displayed in dataLayer for not logged in users? Leave blank for no role.',
				'inpsyde-google-tag-manager'
			),
			'attributes'  => [
				'name'  => self::SETTING__VISITOR_ROLE,
				'type'  => 'text',
				'value' => 'visitor',
			],
		];

		$fields = [
			'label'      => __( 'Fields used in dataLayer', 'inpsyde-google-tag-manager' ),
			'attributes' => [
				'name' => self::SETTING__FIELDS,
				'type' => 'checkbox',
			],
			'choices'    => [
				'ID'               => __( 'ID', 'inpsyde-google-tag-manager' ),
				'role'             => __( 'Role', 'inpsyde-google-tag-manager' ),
				'nickname'         => __( 'Nickname', 'inpsyde-google-tag-manager' ),
				'user_description' => __( 'Description', 'inpsyde-google-tag-manager' ),
				'first_name'       => __( 'First name', 'inpsyde-google-tag-manager' ),
				'last_name'        => __( 'Last name', 'inpsyde-google-tag-manager' ),
				'user_email'       => __( 'E-Mail', 'inpsyde-google-tag-manager' ),
				'url'              => __( 'Url', 'inpsyde-google-tag-manager' ),
			],
		];

		return [
			'label'       => __( 'User', 'inpsyde-google-tag-manager' ),
			'description' => __(
				'Write user data into the Google Tag Manager data layer.',
				'inpsyde-google-tag-manager'
			),
			'attributes'  => [
				'name' => self::SETTING__KEY,
				'type' => 'collection',
			],
			'elements'    => [ $enabled, $visitor, $fields ],
		];
	}
}
