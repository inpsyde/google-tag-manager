<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer\Site;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\Site
 */
class SiteInfoDataCollector implements DataCollectorInterface, SettingsSpecAwareInterface {

	const SETTING__KEY = 'siteInfo';

	const SETTING__ENABLED = 'enabled';
	const SETTING__MULTISITE_FIELDS = 'multisite_fields';
	const SETTING__BLOG_INFO = 'blog_info';

	/**
	 * @var array
	 */
	private $settings = [
		self::SETTING__ENABLED          => DataCollectorInterface::VALUE_DISABLED,
		self::SETTING__MULTISITE_FIELDS => [],
		self::SETTING__BLOG_INFO        => [],
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
	 * @return array
	 */
	public function multisite_fields(): array {

		return $this->settings[ self::SETTING__MULTISITE_FIELDS ];
	}

	/**
	 * @return array
	 */
	public function blog_info_fields(): array {

		return $this->settings[ self::SETTING__BLOG_INFO ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function data(): array {

		$data = [];
		if ( is_multisite() ) {
			$current_site = get_blog_details();

			foreach ( $this->multisite_fields() as $field ) {
				$data[ $field ] = $current_site->{$field} ?? '';
			}
		}

		foreach ( $this->blog_info_fields() as $field ) {
			$data[ $field ] = get_bloginfo( $field );
		}

		return [
			'site' => $data,
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
			'label'      => __( 'Enable/disable site info data', 'inpsyde-google-tag-manager' ),
			'attributes' => [
				'name' => self::SETTING__ENABLED,
				'type' => 'select',
			],
			'choices'    => [
				DataCollectorInterface::VALUE_ENABLED  => __( 'Enabled', 'inpsyde-google-tag-manager' ),
				DataCollectorInterface::VALUE_DISABLED => __( 'Disabled', 'inpsyde-google-tag-manager' ),
			],
		];

		$ms_fields = [
			'label'       => __( 'MultiSite information', 'inpsyde-google-tag-manager' ),
			'attributes'  => [
				'name' => self::SETTING__MULTISITE_FIELDS,
				'type' => 'checkbox',
			],
			'choices'     => [
				'id'         => __( 'ID', 'inpsyde-google-tag-manager' ),
				'network_id' => __( 'Network ID', 'inpsyde-google-tag-manager' ),
				'blogname'   => __( 'Blog name', 'inpsyde-google-tag-manager' ),
				'siteurl'    => __( 'Site url', 'inpsyde-google-tag-manager' ),
				'home'       => __( 'Home', 'inpsyde-google-tag-manager' ),
			],
			'description' => sprintf(
			/* translators: %s is a new sentence which notifies if the user is in or not in a multisite */
				__(
					'This data is only added when a multisite is installed. %s',
					'inpsyde-google-tag-manager'
				),
				is_multisite()
					? __( 'You\'re currently <strong>using</strong> a multisite.', 'inpsyde-google-tag-manager' )
					: __( 'You\'re currently <strong>not using</strong> a multisite.', 'inpsyde-google-tag-manager' )
			),
		];

		$blog_info = [
			'label'      => __( 'Blog information', 'inpsyde-google-tag-manager' ),
			'attributes' => [
				'name' => self::SETTING__BLOG_INFO,
				'type' => 'checkbox',
			],
			'choices'    => [
				'name'        => __( 'Name', 'inpsyde-google-tag-manager' ),
				'description' => __( 'Description', 'inpsyde-google-tag-manager' ),
				'url'         => __( 'Url', 'inpsyde-google-tag-manager' ),
				'charset'     => __( 'Charset', 'inpsyde-google-tag-manager' ),
				'language'    => __( 'Language', 'inpsyde-google-tag-manager' ),
			],
		];

		return [
			'label'      => __( 'Site info', 'inpsyde-google-tag-manager' ),
			'description' => __(
				'Write site info into the Google Tag Manager data layer.',
				'inpsyde-google-tag-manager'
			),
			'attributes' => [
				'name' => self::SETTING__KEY,
				'type' => 'collection',
			],
			'elements'   => [ $enabled, $blog_info, $ms_fields ],
		];
	}
}
