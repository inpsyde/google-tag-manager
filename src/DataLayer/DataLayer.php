<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsRepository;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer
 */
class DataLayer {

	/**
	 * @var DataCollectorInterface[]
	 */
	private $data = [];

	const DATALAYER_NAME = 'dataLayer';

	const SETTING__KEY = 'dataLayer';

	const SETTING__GTM_ID = 'gtm_id';
	const SETTING__AUTO_INSERT_NOSCRIPT = 'auto_insert_noscript';
	const SETTING__DATALAYER_NAME = 'datalayer_name';

	/**
	 * @var array
	 */
	private $settings = [
		self::SETTING__GTM_ID               => '',
		self::SETTING__AUTO_INSERT_NOSCRIPT => DataCollectorInterface::VALUE_ENABLED,
		self::SETTING__DATALAYER_NAME       => self::DATALAYER_NAME,
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
	 * @return string
	 */
	public function id(): string {

		return $this->settings[ self::SETTING__GTM_ID ];
	}

	/**
	 * @return string
	 */
	public function name(): string {

		return $this->settings[ self::SETTING__DATALAYER_NAME ];
	}

	/**
	 * @return bool
	 */
	public function auto_insert_noscript(): bool {

		return $this->settings[ self::SETTING__AUTO_INSERT_NOSCRIPT ] === DataCollectorInterface::VALUE_ENABLED;
	}

	/**
	 * @param DataCollectorInterface $data
	 */
	public function add_data( DataCollectorInterface $data ) {

		$this->data[] = $data;
	}

	/**
	 * @return DataCollectorInterface[]
	 */
	public function data(): array {

		return array_filter(
			$this->data,
			function ( DataCollectorInterface $data ) {

				return $data->is_allowed();
			}
		);
	}
}
