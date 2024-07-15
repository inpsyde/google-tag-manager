<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;

class PostDataCollector implements DataCollectorInterface, SettingsSpecAwareInterface
{

    public const ID = 'postData';
    public const SETTING__POST_FIELDS = 'post_fields';
    public const SETTING__AUTHOR_FIELDS = 'author_fields';

    /**
     * @var array
     */
    private array $settings = [
        self::SETTING__POST_FIELDS => [],
        self::SETTING__AUTHOR_FIELDS => [],
    ];

    /**
     * SiteInfo constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = array_replace_recursive($this->settings, array_filter($settings));
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
    public function data(): ?array
    {
        if (!is_singular()) {
            return null;
        }

        $data = [];

        // Post data
        $fields = [];
        foreach ($this->settings[self::SETTING__POST_FIELDS] as $field) {
            $fields[$field] = get_post_field($field) ?? '';
        }
        $fields = array_filter($fields);
        if (count($fields) > 0) {
            $data['post'] = $fields;
        }
        // Author data
        $fields = [];
        foreach ($this->settings[self::SETTING__AUTHOR_FIELDS] as $field) {
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
     */
    public function settingsSpec(): array
    {
        $postFields = [
            'label' => __('Post fields used in dataLayer', 'inpsyde-google-tag-manager'),
            'attributes' => [
                'name' => self::SETTING__POST_FIELDS,
                'type' => 'checkbox',
            ],
            'choices' => [
                'ID' => __('ID', 'inpsyde-google-tag-manager'),
                'post_title' => __('Title', 'inpsyde-google-tag-manager'),
                'post_name' => __('Name', 'inpsyde-google-tag-manager'),
                'post_author' => __('Author', 'inpsyde-google-tag-manager'),
                'post_date' => __('Date', 'inpsyde-google-tag-manager'),
                'post_date_gmt' => __('Date GMT', 'inpsyde-google-tag-manager'),
                'post_status' => __('Status', 'inpsyde-google-tag-manager'),
                'comment_status' => __('Comment status', 'inpsyde-google-tag-manager'),
                'ping_status' => __('Ping status', 'inpsyde-google-tag-manager'),
                'post_modified' => __('Modified date', 'inpsyde-google-tag-manager'),
                'post_modified_gmt' => __('Modified date GMT', 'inpsyde-google-tag-manager'),
                'post_parent' => __('Parent ID', 'inpsyde-google-tag-manager'),
                'guid' => __('Guid', 'inpsyde-google-tag-manager'),
                'post_type' => __('Post type', 'inpsyde-google-tag-manager'),
                'post_mime_type' => __('Post mime type', 'inpsyde-google-tag-manager'),
                'comment_count' => __('Comment count', 'inpsyde-google-tag-manager'),
            ],
        ];

        $authorFields = [
            'label' => __('Author data', 'inpsyde-google-tag-manager'),
            'description' => __(
                'On single posts, write post author data into the Google Tag Manager data layer.',
                'inpsyde-google-tag-manager'
            ),
            'attributes' => [
                'name' => self::SETTING__AUTHOR_FIELDS,
                'type' => 'checkbox',
            ],
            'choices' => [
                'ID' => __('ID', 'inpsyde-google-tag-manager'),
                'display_name' => __('Name', 'inpsyde-google-tag-manager'),
            ],
        ];

        return [$postFields, $authorFields];
    }
}
