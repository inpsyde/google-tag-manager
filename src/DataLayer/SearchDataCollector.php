<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\Site
 */
class SearchDataCollector implements DataCollectorInterface, SettingsSpecAwareInterface
{
    public const ID = 'search';

    public const SETTING__FIELDS = 'fields';

    /**
     * @var array
     */
    private array $settings = [];

    /**
     * SiteInfo constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = array_replace_recursive($this->settings, array_filter($settings));
    }

    /**
     * {@inheritdoc}
     */
    public function data(): ?array
    {
        global $wp_query;

        if (!is_search()) {
            return null;
        }

        $data = [
            'searchQuery' => get_search_query(),
            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            'searchReferer' => $_SERVER['HTTP_REFERER'] ?? '',
            'searchPostCount' => $wp_query->post_count,
        ];
        foreach ($data as $field => $value) {
            if (!in_array($field, $this->settings[self::SETTING__FIELDS], true)) {
                unset($data[$field]);
            }
        }

        return $data;
    }

    public function id(): string
    {
        return self::ID;
    }

    public function name(): string
    {
        return __('Search', 'inpsyde-google-tag-manager');
    }

    public function description(): string
    {
        return __(
            'Write information about the search into the Google Tag Manager data layer.',
            'inpsyde-google-tag-manager'
        );
    }

    /**
     * @return array
     */
    public function settingsSpec(): array
    {
        return [
            [
                'label' => __('Fields used in dataLayer', 'inpsyde-google-tag-manager'),
                'attributes' => [
                    'name' => self::SETTING__FIELDS,
                    'type' => 'checkbox',
                ],
                'choices' => [
                    'searchQuery' => __('Search query', 'inpsyde-google-tag-manager'),
                    'searchReferer' => __('Post referer', 'inpsyde-google-tag-manager'),
                    'searchPostCount' => __('Count found posts', 'inpsyde-google-tag-manager'),
                ],
            ],
        ];
    }
}
