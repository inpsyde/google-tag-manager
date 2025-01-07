<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;

class PostDataCollector implements DataCollector, SettingsSpecification
{
    public const ID = 'postData';
    public const SETTING__POST_FIELDS = 'post_fields';
    public const SETTING__AUTHOR_FIELDS = 'author_fields';

    /**
     * @var array
     */
    private const DEFAULTS = [
        self::SETTING__POST_FIELDS => [],
        self::SETTING__AUTHOR_FIELDS => [],
    ];

    protected function __construct()
    {
    }

    public static function new(): PostDataCollector
    {
        return new self();
    }

    public function id(): string
    {
        return self::ID;
    }

    public function name(): string
    {
        return __('Post data', 'inpsyde-google-tag-manager');
    }

    public function description(): string
    {
        return __('Collect Post and Author data into dataLayer.', 'inpsyde-google-tag-manager');
    }

    /**
     * {@inheritdoc}
     */
    public function data(array $settings): ?array
    {
        if (!is_singular()) {
            return null;
        }

        $data = [];

        // Post data
        $fields = [];
        foreach ($settings[self::SETTING__POST_FIELDS] as $field) {
            /** @psalm-suppress DocblockTypeContradiction, RedundantConditionGivenDocblockType */
            $fields[$field] = get_post_field($field) ?? '';
        }
        $fields = array_filter($fields);
        if (count($fields) > 0) {
            $data['post'] = $fields;
        }
        // Author data
        $fields = [];
        foreach ($settings[self::SETTING__AUTHOR_FIELDS] as $field) {
            /** @psalm-suppress DocblockTypeContradiction, RedundantConditionGivenDocblockType */
            $fields[$field] = get_the_author_meta($field) ?? '';
        }
        $fields = array_filter($fields);
        if (count($fields) > 0) {
            $data['author'] = $fields;
        }

        return count($data) > 0
            ? $data
            : null;
    }

    /**
     * @return array
     *  phpcs:disable Syde.Functions.FunctionLength.TooLong
     */
    public function specification(): array
    {
        $postFields = [
            'label' => __('Post fields used in dataLayer', 'inpsyde-google-tag-manager'),
            'name' => self::SETTING__POST_FIELDS,
            'type' => 'checkbox',
            'choices' => [
                [
                    'label' => __('ID', 'inpsyde-google-tag-manager'),
                    'value' => 'ID',
                ],
                [
                    'label' => __('Title', 'inpsyde-google-tag-manager'),
                    'value' => 'post_title',
                ],
                [
                    'label' => __('Name', 'inpsyde-google-tag-manager'),
                    'value' => 'post_name',
                ],
                [
                    'label' => __('Author', 'inpsyde-google-tag-manager'),
                    'value' => 'post_author',
                ],
                [
                    'label' => __('Date', 'inpsyde-google-tag-manager'),
                    'value' => 'post_date',
                ],
                [
                    'label' => __('Date GMT', 'inpsyde-google-tag-manager'),
                    'value' => 'post_date_gmt',
                ],
                [
                    'label' => __('Status', 'inpsyde-google-tag-manager'),
                    'value' => 'post_status',
                ],
                [
                    'label' => __('Comment status', 'inpsyde-google-tag-manager'),
                    'value' => 'comment_status',
                ],
                [
                    'label' => __('Ping status', 'inpsyde-google-tag-manager'),
                    'value' => 'ping_status',
                ],
                [
                    'label' => __('Modified date', 'inpsyde-google-tag-manager'),
                    'value' => 'post_modified',
                ],
                [
                    'label' => __('Modified date GMT', 'inpsyde-google-tag-manager'),
                    'value' => 'post_modified_gmt',
                ],
                [
                    'label' => __('Parent ID', 'inpsyde-google-tag-manager'),
                    'value' => 'post_parent',
                ],
                [
                    'label' => __('Guid', 'inpsyde-google-tag-manager'),
                    'value' => 'guid',
                ],
                [
                    'label' => __('Post type', 'inpsyde-google-tag-manager'),
                    'value' => 'post_type',
                ],
                [
                    'label' => __('Post mime type', 'inpsyde-google-tag-manager'),
                    'value' => 'post_mime_type',
                ],
                [
                    'label' => __('Comment count', 'inpsyde-google-tag-manager'),
                    'value' => 'comment_count',
                ],
            ],
        ];

        $authorFields = [
            'label' => __('Author data', 'inpsyde-google-tag-manager'),
            'description' => __(
                'On single posts, write post author data into the Google Tag Manager data layer.',
                'inpsyde-google-tag-manager',
            ),
            'name' => self::SETTING__AUTHOR_FIELDS,
            'type' => 'checkbox',
            'choices' => [
                [
                    'label' => __('ID', 'inpsyde-google-tag-manager'),
                    'value' => 'ID',
                ],
                [
                    'label' => __('Name', 'inpsyde-google-tag-manager'),
                    'value' => 'display_name',
                ],
            ],
        ];

        return [$postFields, $authorFields];
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
