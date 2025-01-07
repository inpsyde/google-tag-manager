<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Event\NoscriptTagRendererEvent;
use Inpsyde\GoogleTagManager\Service\DataCollectorRegistry;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer
 */
class DataLayer implements SettingsSpecification
{
    public const ID = 'dataLayer';
    public const DATALAYER_NAME = 'dataLayer';
    public const SETTING__GTM_ID = 'gtm_id';
    public const SETTING__AUTO_INSERT_NOSCRIPT = 'auto_insert_noscript';
    public const SETTING__DATALAYER_NAME = 'datalayer_name';

    public const SETTING_ENABLED_COLLECTORS = 'enabled_collectors';

    private const DEFAULTS = [
        self::SETTING__GTM_ID => '',
        self::SETTING__AUTO_INSERT_NOSCRIPT => DataCollector::VALUE_ENABLED,
        self::SETTING__DATALAYER_NAME => self::DATALAYER_NAME,
        self::SETTING_ENABLED_COLLECTORS => [],
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $settings = [];

    protected SettingsRepository $settingsRepo;

    protected DatacollectorRegistry $registry;

    protected function __construct(SettingsRepository $settingsRepo, DataCollectorRegistry $registry)
    {
        $this->settingsRepo = $settingsRepo;
        $settings = (array) $settingsRepo->option(self::ID);
        $this->settings = $this->sanitize($settings);
        $this->registry = $registry;
    }

    public static function new(SettingsRepository $settingsRepo, DataCollectorRegistry $registry): DataLayer
    {
        return new self($settingsRepo, $registry);
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return self::ID;
    }

    /**
     * @return string
     */
    public function gtmId(): string
    {
        return $this->settings[self::SETTING__GTM_ID];
    }

    /**
     * @return string
     */
    public function dataLayerName(): string
    {
        return $this->settings[self::SETTING__DATALAYER_NAME];
    }

    /**
     * @return bool
     */
    public function autoInsertNoscript(): bool
    {
        $autoInsert = $this->settings[self::SETTING__AUTO_INSERT_NOSCRIPT];

        return $autoInsert === DataCollector::VALUE_ENABLED;
    }

    public function enabledCollectors(): array
    {
        return $this->settings[self::SETTING_ENABLED_COLLECTORS];
    }

    /**
     * @return array<string, array>
     */
    public function data(): array
    {
        $data = [];
        /** @var DataCollector $collector */
        foreach ($this->registry->all() as $collector) {
            if (!in_array($collector->id(), $this->enabledCollectors(), true)) {
                continue;
            }

            $settings = [];
            if ($collector instanceof SettingsSpecification) {
                $settings = $this->settingsRepo->option($collector->id());
                $settings = $collector->sanitize($settings);
            }

            $collectorData = $collector->data($settings);
            if ($collectorData !== null) {
                $data[$collector->id()] = $collectorData;
            }
        }

        return $data;
    }

    /**
     * @return array
     * phpcs:disable Syde.Functions.FunctionLength.TooLong
     * phpcs:disable Syde.Functions.LineLength.TooLong
     */
    public function specification(): array
    {
        $gtmId = [
            'label' => __('Google Tag Manager ID', 'inpsyde-google-tag-manager'),
            'name' => static::SETTING__GTM_ID,
            'type' => 'text',
        ];

        $noscriptDesc = [];
        $noscriptDesc[] = sprintf(
        /* translators: %1$s is <body> and %2$s <noscript> */
            __(
                'If enabled, the plugin tries automatically to insert the %1$s after the %2$s tag.',
                'inpsyde-google-tag-manager',
            ),
            '<body>',
            '<noscript>',
        );
        $noscriptDesc[] = sprintf(
        /* translators: %1$s is <body> and %2$s the do_action( .. ); */
            __(
                'This may cause problems with other plugins, so to be safe, disable this feature and add to your theme after %1$s following: %2$s',
                'inpsyde-google-tag-manager',
            ),
            '<body>',
            '<?php do_action( "' . NoscriptTagRendererEvent::ACTION_RENDER . '" ); ?>',
        );

        $noscript = [
            'label' => __('Auto insert noscript in body', 'inpsyde-google-tag-manager'),
            'description' => implode(" ", $noscriptDesc),
            'name' => self::SETTING__AUTO_INSERT_NOSCRIPT,
            'type' => 'select',
            'choices' => [
                [
                    'label' => __('Enable', 'inpsyde-google-tag-manager'),
                    'value' => DataCollector::VALUE_ENABLED,
                ],
                [
                    'label' => __('Disable', 'inpsyde-google-tag-manager'),
                    'value' => DataCollector::VALUE_DISABLED,
                ],
            ],
        ];

        $dataLayer = [
            'label' => __('dataLayer name', 'inpsyde-google-tag-manager'),
            'description' => __(
                'In some cases you have to rename the <var>dataLayer</var>-variable. Default: dataLayer',
                'inpsyde-google-tag-manager',
            ),
            'name' => self::SETTING__DATALAYER_NAME,
            'type' => 'text',
        ];

        $enabledCollectors = [
            'label' => __('Enable collectors', 'inpsyde-google-tag-manager'),
            'name' => self::SETTING_ENABLED_COLLECTORS,
            'type' => 'checkbox',
            'choices' => (function (): array {
                $choices = [];
                foreach ($this->registry->all() as $data) {
                    $choices[] = [
                        'label' => $data->name(),
                        'value' => $data->id(),
                    ];
                }

                return $choices;
            })(),
        ];

        return [$gtmId, $noscript, $dataLayer, $enabledCollectors];
    }

    public function validate(array $data): ?\WP_Error
    {
        $gtmId = $data[self::SETTING__GTM_ID] ?? '';
        if (!preg_match('/^GTM-[A-Z0-9]+$/', $gtmId)) {
            /** phpcs:disable Syde.Files.LineLength.TooLong */
            $message = __('The input does not match against pattern GTM-[A-Z0-9]', 'inpsyde-google-tag-manager');
            return new \WP_Error(static::SETTING__GTM_ID, $message);
        }

        return null;
    }

    public function sanitize(array $data): array
    {
        return array_replace_recursive(self::DEFAULTS, array_filter($data));
    }
}
