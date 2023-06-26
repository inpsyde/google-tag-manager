<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\Site
 */
class SiteInfoDataCollector implements DataCollectorInterface, SettingsSpecAwareInterface
{

    public const SETTING__KEY = 'siteInfo';
    public const SETTING__ENABLED = 'enabled';
    public const SETTING__MULTISITE_FIELDS = 'multisite_fields';
    public const SETTING__BLOG_INFO = 'blog_info';

    /**
     * @var array
     */
    private array $settings = [
        self::SETTING__ENABLED => DataCollectorInterface::VALUE_DISABLED,
        self::SETTING__MULTISITE_FIELDS => [],
        self::SETTING__BLOG_INFO => [],
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
        $data = [];
        if (is_multisite()) {
            $currentSite = get_blog_details();

            foreach ($this->multisiteFields() as $field) {
                $data[$field] = $currentSite->{$field} ?? '';
            }
        }

        foreach ($this->blogInfoFields() as $field) {
            $data[$field] = get_bloginfo($field);
        }

        return ['site' => $data];
    }

    /**
     * @return array
     */
    public function multisiteFields(): array
    {
        return $this->settings[self::SETTING__MULTISITE_FIELDS];
    }

    /**
     * @return array
     */
    public function blogInfoFields(): array
    {
        return $this->settings[self::SETTING__BLOG_INFO];
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
    // phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
    // phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
    public function settingsSpec(): array
    {
        $enabled = [
            'label' => __('Enable/disable site info data', 'inpsyde-google-tag-manager'),
            'attributes' => [
                'name' => self::SETTING__ENABLED,
                'type' => 'select',
            ],
            'choices' => [
                DataCollectorInterface::VALUE_ENABLED => __('Enabled', 'inpsyde-google-tag-manager'),
                DataCollectorInterface::VALUE_DISABLED => __('Disabled', 'inpsyde-google-tag-manager'),
            ],
        ];

        $multisiteNotice = is_multisite()
            ? __(
                'You\'re currently <strong>using</strong> a multisite.',
                'inpsyde-google-tag-manager'
            )
            : __(
                'You\'re currently <strong>not using</strong> a multisite.',
                'inpsyde-google-tag-manager'
            );

        $multisiteFields = [
            'label' => __('MultiSite information', 'inpsyde-google-tag-manager'),
            'attributes' => [
                'name' => self::SETTING__MULTISITE_FIELDS,
                'type' => 'checkbox',
            ],
            'choices' => [
                'id' => __('ID', 'inpsyde-google-tag-manager'),
                'network_id' => __('Network ID', 'inpsyde-google-tag-manager'),
                'blogname' => __('Blog name', 'inpsyde-google-tag-manager'),
                'siteurl' => __('Site url', 'inpsyde-google-tag-manager'),
                'home' => __('Home', 'inpsyde-google-tag-manager'),
            ],
            'description' => sprintf(
                /* translators: %s is a new sentence which notifies if the user is in or not in a multisite */
                __(
                    'This data is only added when a multisite is installed. %s',
                    'inpsyde-google-tag-manager'
                ),
                $multisiteNotice
            ),
        ];

        $blogInfo = [
            'label' => __('Blog information', 'inpsyde-google-tag-manager'),
            'attributes' => [
                'name' => self::SETTING__BLOG_INFO,
                'type' => 'checkbox',
            ],
            'choices' => [
                'name' => __('Name', 'inpsyde-google-tag-manager'),
                'description' => __('Description', 'inpsyde-google-tag-manager'),
                'url' => __('Url', 'inpsyde-google-tag-manager'),
                'charset' => __('Charset', 'inpsyde-google-tag-manager'),
                'language' => __('Language', 'inpsyde-google-tag-manager'),
            ],
        ];

        return [
            'label' => __('Site info', 'inpsyde-google-tag-manager'),
            'description' => __(
                'Write site info into the Google Tag Manager data layer.',
                'inpsyde-google-tag-manager'
            ),
            'attributes' => [
                'name' => self::SETTING__KEY,
                'type' => 'collection',
            ],
            'elements' => [$enabled, $blogInfo, $multisiteFields],
        ];
        // phpcs:enable Inpsyde.CodeQuality.FunctionLength.TooLong
    }
}
