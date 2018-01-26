<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\Filter\ArrayValue;
use Inpsyde\Filter\WordPress\StripTags;
use Inpsyde\GoogleTagManager\Event\NoscriptTagRendererEvent;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;
use Inpsyde\Validator\DataValidator;
use Inpsyde\Validator\RegEx;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer
 */
class DataLayer implements SettingsSpecAwareInterface
{

    const DATALAYER_NAME = 'dataLayer';
    const SETTING__KEY = 'dataLayer';
    const SETTING__GTM_ID = 'gtm_id';
    const SETTING__AUTO_INSERT_NOSCRIPT = 'auto_insert_noscript';
    const SETTING__DATALAYER_NAME = 'datalayer_name';
    /**
     * @var DataCollectorInterface[]
     */
    private $data = [];
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
    public function __construct(SettingsRepository $repository)
    {

        $settings       = $repository->getOption(self::SETTING__KEY);
        $this->settings = array_replace_recursive($this->settings, array_filter($settings));
    }

    /**
     * @return string
     */
    public function id(): string
    {

        return $this->settings[ self::SETTING__GTM_ID ];
    }

    /**
     * @return string
     */
    public function name(): string
    {

        return $this->settings[ self::SETTING__DATALAYER_NAME ];
    }

    /**
     * @return bool
     */
    public function autoInsertNoscript(): bool
    {

        return $this->settings[ self::SETTING__AUTO_INSERT_NOSCRIPT ] === DataCollectorInterface::VALUE_ENABLED;
    }

    /**
     * @param DataCollectorInterface $data
     */
    public function addData(DataCollectorInterface $data)
    {

        $this->data[] = $data;
    }

    /**
     * @return DataCollectorInterface[]
     */
    public function data(): array
    {

        return array_filter($this->data, function (DataCollectorInterface $data) {

            return $data->isAllowed();
        });
    }

    /**
     * @return array
     * phpcs:disable ObjectCalisthenics.Files.FunctionLength
     */
    public function settingsSpec(): array
    {
        $gtm_id = [
            'label'      => __('Google Tag Manager ID', 'inpsyde-google-tag-manager'),
            'attributes' => [
                'name' => self::SETTING__GTM_ID,
                'type' => 'text',
            ],
        ];

        $noscript = [
            'label'       => __('Auto insert noscript in body', 'inpsyde-google-tag-manager'),
            'description' => sprintf(
                /* translators: %s is the name of NoscriptTagRendererEvent::ACTION_RENDER_NOSCRIPT */
                __(
                    'If enabled, the plugin tries automatically to insert the <code>&lt;noscript&gt</code>-tag  ' .
                    'after the <code>&lt;body&gt;</code>-tag. This may cause problems with other plugins, so to ' .
                    'be safe, disable this feature and add to your theme after <code>&lt;body&gt;</code> following:' .
                    '<pre><code>&lt;?php do_action( "%s" ); ?&gt;</code></pre>',
                    'inpsyde-google-tag-manager'
                ),
                NoscriptTagRendererEvent::ACTION_RENDER
            ),
            'attributes'  => [
                'name' => self::SETTING__AUTO_INSERT_NOSCRIPT,
                'type' => 'select',
            ],
            'choices'     => [
                DataCollectorInterface::VALUE_ENABLED  => __('Enable', 'inpsyde-google-tag-manager'),
                DataCollectorInterface::VALUE_DISABLED => __('Disable', 'inpsyde-google-tag-manager'),
            ],
        ];

        $data_layer = [
            'label'       => __('dataLayer name', 'inpsyde-google-tag-manager'),
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
            'label'       => __('General', 'inpsyde-google-tag-manager'),
            'description' => __(
                'More information about Google Tag Manager can be found in ' .
                '<a href="https://support.google.com/tagmanager/#topic=3441530">Google Tag Manager Help Center</a>.',
                'inpsyde-google-tag-manager'
            ),
            'attributes'  => [
                'name' => DataLayer::SETTING__KEY,
                'type' => 'collection',
            ],
            'elements'    => [$gtm_id, $noscript, $data_layer],
            'validators'  => [
                (new DataValidator())->add_validator_by_key(
                    new RegEx(['pattern' => '/^GTM-[A-Z0-9]+$/',]),
                    DataLayer::SETTING__GTM_ID
                ),
            ],
            'filters'     => [
                (new ArrayValue())->add_filter(new StripTags()),
            ],
        ];
        // phpcs:disable ObjectCalisthenics.Files.FunctionLength
    }
}
