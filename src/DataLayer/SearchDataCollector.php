<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\Site
 */
class SearchDataCollector implements DataCollector, SettingsSpecification
{
    public const ID = 'search';

    public const SETTING__FIELDS = 'fields';

    /**
     * @var array
     */
    private const DEFAULTS = [
        self::SETTING__FIELDS => [],
    ];

    protected function __construct()
    {
    }

    public static function new(): SearchDataCollector
    {
        return new self();
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
     * {@inheritdoc}
     */
    public function data(array $settings): ?array
    {
        global $wp_query;

        if (!is_search()) {
            return null;
        }

        $data = [
            'query' => get_search_query(),
            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            'referer' => $_SERVER['HTTP_REFERER'] ?? '',
            'post_count' => $wp_query->post_count,
        ];
        foreach ($data as $field => $value) {
            if (!in_array($field, $settings[self::SETTING__FIELDS], true)) {
                unset($data[$field]);
            }
        }

        return ['search' => $data];
    }

    /**
     * @return array
     */
    public function specification(): array
    {
        return [
            [
                'label' => __('Fields used in dataLayer', 'inpsyde-google-tag-manager'),
                'name' => self::SETTING__FIELDS,
                'type' => 'checkbox',
                'choices' => [
                    [
                        'label' => __('Search query', 'inpsyde-google-tag-manager'),
                        'value' => 'query',
                    ],
                    [
                        'label' => __('Post referer', 'inpsyde-google-tag-manager'),
                        'value' => 'referer',
                    ],
                    [
                        'label' => __('Count found posts', 'inpsyde-google-tag-manager'),
                        'value' => 'post_count',
                    ],
                ],
            ],
        ];
    }

    public function validate(array $data): ?\WP_Error
    {
        return null;
    }

    public function sanitize(array $data): array
    {
        return array_replace_recursive(self::DEFAULTS, array_filter($data));
    }
}
