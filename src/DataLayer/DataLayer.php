<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\Filter\ArrayValue;
use Inpsyde\Filter\WordPress\StripTags;
use Inpsyde\GoogleTagManager\Renderer\NoscriptTagRenderer;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;
use Inpsyde\Validator\DataValidator;
use Inpsyde\Validator\RegEx;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer
 */
class DataLayer implements SettingsSpecAwareInterface {

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

	/**
	 * @return array
	 */
	public function settings_spec(): array {

		$gtm_id = [
			'label'      => __( 'Google Tag Manager ID', 'inpsyde-google-tag-manager' ),
			'attributes' => [
				'name' => self::SETTING__GTM_ID,
				'type' => 'text',
			],
		];

		$noscript = [
			'label'       => __( 'Auto insert noscript in body', 'inpsyde-google-tag-manager' ),
			'description' => sprintf(
			/* translators: %s is the name of the action which can be found in Renderer\NoscriptTagRenderer::ACTION_RENDER_NOSCRIPT */
				__(
					'If enabled, the Plugin tries automatically to insert the <code>&lt;noscript&gt</code>-tag after the <code>&lt;body&gt;</code>-tag</code>. This may can cause problems with other plugins, so to be safe, disable this feature and add to your theme after <code>&lt;body&gt;</code> following: <pre><code>&lt;?php do_action( "%s" ); ?&gt;</code></pre>',
					'inpsyde-google-tag-manager'
				),
				NoscriptTagRenderer::ACTION_RENDER_NOSCRIPT
			),
			'attributes'  => [
				'name' => self::SETTING__AUTO_INSERT_NOSCRIPT,
				'type' => 'select',
			],
			'choices'     => [
				DataCollectorInterface::VALUE_ENABLED  => __( 'Enable', 'inpsyde-google-tag-manager' ),
				DataCollectorInterface::VALUE_DISABLED => __( 'Disable', 'inpsyde-google-tag-manager' ),
			],
		];

		$data_layer = [
			'label'       => __( 'dataLayer name', 'inpsyde-google-tag-manager' ),
			'description' => __(
				'In some cases you have to rename the <var>dataLayer</var>-variable. Default: dataLayer',
				'inpsyde-google-tag-manager'
			),
			'attributes'  => [
				'name' => self::SETTING__DATALAYER_NAME,
				'type' => 'text',
			],
		];

		return [
			'label'       => __( 'DataLayer', 'inpsyde-google-tag-manager' ),
			'description' => __(
				'More information about Google Tag Manager can be found in <a href="https://developers.google.com/tag-manager/">Google Tag Manager Help Center</a>.',
				'inpsyde-google-tag-manager'
			),
			'attributes'  => [
				'name' => DataLayer::SETTING__KEY,
				'type' => 'collection',
			],
			'elements'    => [ $gtm_id, $noscript, $data_layer ],
			'validators'  => [
				( new DataValidator() )->add_validator_by_key(
					new RegEx(
						[
							'pattern' => '/^GTM-[A-Z0-9]+$/',
						]
					), DataLayer::SETTING__GTM_ID
				),
			],
			'filters'     => [
				( new ArrayValue() )->add_filter( new StripTags() ),
			],
		];
	}
}
