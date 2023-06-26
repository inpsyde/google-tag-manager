<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;

class AuthorDataCollector implements DataCollectorInterface, SettingsSpecAwareInterface
{

    public const SETTING__KEY = 'authorData';
    public const SETTING__ENABLED = 'enabled';
    public const SETTING__FIELDS = 'fields';

    /**
     * @var array
     */
    private array $settings = [
        self::SETTING__ENABLED => DataCollectorInterface::VALUE_DISABLED,
        self::SETTING__FIELDS => [],
    ];

    /**
     * SiteInfo constructor.
     *
     * @param SettingsRepository $repository
     */
    public function __construct(SettingsRepository $repository)
    {
        $settings = $repository->option(self::SETTING__KEY);
        $this->settings = array_replace_recursive($this->settings, array_filter($settings));
    }

    /**
     * {@inheritdoc}
     */
    public function data(): array
    {
        if (!is_single()) {
            return [];
        }

        $data = [];
        foreach ($this->fields() as $field) {
            $data[$field] = get_the_author_meta($field) ?? '';
        }

        return ['author' => $data];
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return $this->settings[self::SETTING__FIELDS];
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed(): bool
    {
        return $this->enabled();
    }

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return $this->settings[self::SETTING__ENABLED] === DataCollectorInterface::VALUE_ENABLED;
    }

    /**
     * @return array
     */
    public function settingsSpec(): array
    {
        $enabled = [
            'label' => __('Enable/disable author data', 'inpsyde-google-tag-manager'),
            'attributes' => [
                'name' => self::SETTING__ENABLED,
                'type' => 'select',
            ],
            'choices' => [
                DataCollectorInterface::VALUE_ENABLED => __(
                    'Enabled',
                    'inpsyde-google-tag-manager'
                ),
                DataCollectorInterface::VALUE_DISABLED => __(
                    'Disabled',
                    'inpsyde-google-tag-manager'
                ),
            ],
        ];

        $fields = [
            'label' => __('Fields used in dataLayer', 'inpsyde-google-tag-manager'),
            'attributes' => [
                'name' => self::SETTING__FIELDS,
                'type' => 'checkbox',
            ],
            'choices' => [
                'ID' => __('ID', 'inpsyde-google-tag-manager'),
                'display_name' => __('Name', 'inpsyde-google-tag-manager'),
            ],
        ];

        return [
            'label' => __('Author', 'inpsyde-google-tag-manager'),
            'description' => __(
                'On single posts, write post author data into the Google Tag Manager data layer.',
                'inpsyde-google-tag-manager'
            ),
            'attributes' => [
                'name' => self::SETTING__KEY,
                'type' => 'collection',
            ],
            'elements' => [$enabled, $fields],
        ];
    }
}
