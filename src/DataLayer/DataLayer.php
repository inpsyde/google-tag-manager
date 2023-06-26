<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

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
    public const DATALAYER_NAME = 'dataLayer';
    public const SETTING__KEY = 'dataLayer';
    public const SETTING__GTM_ID = 'gtm_id';
    public const SETTING__AUTO_INSERT_NOSCRIPT = 'auto_insert_noscript';
    public const SETTING__DATALAYER_NAME = 'datalayer_name';

    /**
     * @var DataCollectorInterface[]
     */
    private array $data = [];

    /**
     * @var array
     */
    private array $settings = [
        self::SETTING__GTM_ID => '',
        self::SETTING__AUTO_INSERT_NOSCRIPT => DataCollectorInterface::VALUE_ENABLED,
        self::SETTING__DATALAYER_NAME => self::DATALAYER_NAME,
    ];

    /**
     * SiteInfo constructor.
     *
     * @param SettingsRepository $repository
     */
    public function __construct(SettingsRepository $repository)
    {
        $settings = (array) $repository->option(self::SETTING__KEY);
        $this->settings = array_replace_recursive($this->settings, array_filter($settings));
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->settings[self::SETTING__GTM_ID];
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->settings[self::SETTING__DATALAYER_NAME];
    }

    /**
     * @return bool
     */
    public function autoInsertNoscript(): bool
    {
        $autoInsert = $this->settings[self::SETTING__AUTO_INSERT_NOSCRIPT];

        return $autoInsert === DataCollectorInterface::VALUE_ENABLED;
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
        return array_filter(
            $this->data,
            static function (DataCollectorInterface $data): bool {
                return $data->isAllowed();
            }
        );
    }

    /**
     * @return array
     * @throws \Inpsyde\Validator\Exception\InvalidArgumentException
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     * phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
     */
    public function settingsSpec(): array
    {
        $stripTagsFilter = static function (string $value): string {
            return wp_strip_all_tags($value, true);
        };

        $gtmId = [
            'label' => __('Google Tag Manager ID', 'inpsyde-google-tag-manager'),
            'attributes' => [
                'name' => static::SETTING__GTM_ID,
                'type' => 'text',
            ],
            'filter' => $stripTagsFilter,
            'validator' => static function (string $value): ?\WP_Error {
                // phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
                if (!@preg_match('/^GTM-[A-Z0-9]+$/', $value)) {
                    return new \WP_Error(
                        static::SETTING__GTM_ID,
                        __('The input does not match against pattern GTM-[A-Z0-9]', 'inpsyde-google-tag-manager')
                    );
                }

                return null;
            },
        ];

        $noscriptDesc = [];
        $noscriptDesc[] = sprintf(
        /* translators: %1$s is <body> and %2$s <noscript> */
            __(
                'If enabled, the plugin tries automatically to insert the %1$s after the %2$s tag.',
                'inpsyde-google-tag-manager'
            ),
            '<code>&lt;body&gt;</code>',
            '<code>&lt;noscript&gt</code>'
        );
        $noscriptDesc[] = sprintf(
        /* translators: %1$s is <body> and %2$s the do_action( .. ); */
            __(
                'This may cause problems with other plugins, so to be safe, disable this feature and add to your theme after %1$s following %2$s',
                'inpsyde-google-tag-manager'
            ),
            '<code>&lt;body&gt;</code>',
            '<pre><code>&lt;?php do_action( "' . NoscriptTagRendererEvent::ACTION_RENDER . '" ); ?&gt;</code></pre>'
        );

        $noscript = [
            'label' => __('Auto insert noscript in body', 'inpsyde-google-tag-manager'),
            'description' => implode(" ", $noscriptDesc),
            'attributes' => [
                'name' => self::SETTING__AUTO_INSERT_NOSCRIPT,
                'type' => 'select',
            ],
            'choices' => [
                DataCollectorInterface::VALUE_ENABLED => __('Enable', 'inpsyde-google-tag-manager'),
                DataCollectorInterface::VALUE_DISABLED => __('Disable', 'inpsyde-google-tag-manager'),
            ],
            'filter' => $stripTagsFilter,
        ];

        $dataLayer = [
            'label' => __('dataLayer name', 'inpsyde-google-tag-manager'),
            'description' => __(
                'In some cases you have to rename the <var>dataLayer</var>-variable. Default: dataLayer',
                'inpsyde-google-tag-manager'
            ),
            'attributes' => [
                'name' => self::SETTING__DATALAYER_NAME,
                'type' => 'text',
            ],
            'filter' => $stripTagsFilter,
        ];

        return [
            'label' => __('General', 'inpsyde-google-tag-manager'),
            'description' => __(
                'More information about Google Tag Manager can be found in <a href="https://support.google.com/tagmanager/#topic=3441530">Google Tag Manager Help Center</a>.',
                'inpsyde-google-tag-manager'
            ),
            'attributes' => [
                'name' => DataLayer::SETTING__KEY,
                'type' => 'collection',
            ],
            'elements' => [$gtmId, $noscript, $dataLayer],
        ];
    }
}
