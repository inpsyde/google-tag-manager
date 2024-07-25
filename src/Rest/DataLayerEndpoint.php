<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Rest;

use Inpsyde\GoogleTagManager\DataLayer\DataCollector;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Service\DataCollectorRegistry;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;

class DataLayerEndpoint implements RestEndpoint
{
    /**
     * @param DataLayer $dataLayer
     * @param SettingsRepository $repository
     *
     * @see DataLayerEndpoint::new()
     */
    protected function __construct(protected DataLayer $dataLayer, protected DataCollectorRegistry $registry, protected SettingsRepository $repository)
    {
    }

    public static function new(DataLayer $dataLayer, DataCollectorRegistry $registry, SettingsRepository $repository): DataLayerEndpoint
    {
        return new self($dataLayer, $registry, $repository);
    }

    public static function base(): string
    {
        return 'data-layer';
    }

    public function routes(): array
    {
        $base = $this->base();

        return [
            "/{$base}/" => [
                [
                    'label' => __('DataLayer', 'inpsyde-google-tag-manager'),
                    'methods' => [\WP_REST_Server::READABLE],
                    'callback' => [$this, 'fetchDataLayer'],
                    'permission_callback' => static function (): bool {
                        return current_user_can('manage_options');
                    },
                    'entityName' => 'data-layer',
                    'entityBaseUrl' => "/{$base}/",
                ],
                [
                    'label' => __('DataLayer', 'inpsyde-google-tag-manager'),
                    'methods' => [\WP_REST_Server::CREATABLE],
                    'callback' => [$this, 'updateDataLayer'],
                    'permission_callback' => static function (): bool {
                        return current_user_can('manage_options');
                    },
                ],
            ],
        ];
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return \WP_REST_Response
     *
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function updateDataLayer(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $settings = $request->get_json_params();

            $data = $this->defaultData();
            $data['settings'] = $settings;

            $allErrors = [];
            $dataLayerSettings = $settings[$this->dataLayer->id()] ?? [];
            $dataLayerSettings = $this->dataLayer->sanitize($dataLayerSettings);
            $error = $this->dataLayer->validate($dataLayerSettings);
            // phpcs:disable Inpsyde.CodeQuality.NoElse.ElseFound
            if ($error !== null) {
                $allErrors[$this->dataLayer->id()] = $error->errors;
            } else {
                $settings[$this->dataLayer->id()] = $dataLayerSettings;
            }

            /** @var DataCollector $collector */
            foreach ($this->registry->all() as $collector) {
                if (!$collector instanceof SettingsSpecification) {
                    unset($settings[$collector->id()]);
                    continue;
                }
                $isEnabled = in_array(
                    $collector->id(),
                    (array) $dataLayerSettings[DataLayer::SETTING_ENABLED_COLLECTORS],
                    true
                );
                if (!$isEnabled) {
                    $settings[$collector->id()] = $collector->sanitize([]);
                    continue;
                }

                $collectorSettings = $collector->sanitize($settings[$collector->id()] ?? []);
                $error = $collector->validate($collectorSettings);
                if ($error !== null) {
                    $allErrors[$collector->id()] = $error->errors;
                    continue;
                }

                $settings[$collector->id()] = $collectorSettings;
            }

            if (count($allErrors) > 0) {
                $data['errors'] = $allErrors;

                return new \WP_REST_Response(
                    RestResponseData::new(
                        'Errors found.',
                        true,
                        $data
                    )
                );
            }

            $this->repository->update($settings);
            $data['settings'] = $settings;

            return new \WP_REST_Response(
                RestResponseData::new(
                    'Successfully saved.',
                    true,
                    $data
                )
            );
        } catch (\Throwable $throwable) {
            return new \WP_REST_Response(
                RestResponseData::fromThrowable($throwable)
            );
        }
    }

    public function fetchDataLayer(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $data = $this->defaultData();

            return new \WP_REST_Response(
                RestResponseData::new('Successfully loaded.', true, $data)
            );
        } catch (\Throwable $throwable) {
            return new \WP_REST_Response(
                RestResponseData::fromThrowable($throwable)
            );
        }
    }

    protected function defaultData(): array
    {
        $data = [
            'dataLayer' => [
                'id' => $this->dataLayer->id(),
                'specification' => $this->dataLayer->specification(),
            ],
            'collectors' => [],
            'settings' => $this->repository->options(),
            'errors' => [],
        ];

        $collectors = [];
        foreach ($this->registry->all() as $collector) {
            $collectors[] = [
                'id' => $collector->id(),
                'name' => $collector->name(),
                'description' => $collector->description(),
                'specification' => $collector instanceof SettingsSpecification
                    ? $collector->specification()
                    : [],
            ];
        }
        $data['collectors'] = $collectors;

        return $data;
    }
}
