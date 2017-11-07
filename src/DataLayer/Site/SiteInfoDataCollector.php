<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer\Site;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\Site
 */
class SiteInfoDataCollector implements DataCollectorInterface {

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
		self::SETTING__BLOG_INFO        => []
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

		return [ "site" => $data ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_allowed(): bool {

		return $this->enabled();
	}
}