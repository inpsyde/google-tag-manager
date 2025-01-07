<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\Site
 */
class SiteInfoDataCollector implements DataCollector, SettingsSpecification
{
    public const ID = 'siteInfo';
    public const SETTING__MULTISITE_FIELDS = 'multisite_fields';
    public const SETTING__BLOG_INFO = 'blog_info';

    /**
     * @var array
     */
    private const DEFAULTS = [
        self::SETTING__MULTISITE_FIELDS => [],
        self::SETTING__BLOG_INFO => [],
    ];

    protected function __construct()
    {
    }

    public static function new(): SiteInfoDataCollector
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function data(array $settings): ?array
    {
        $data = [];
        if (is_multisite()) {
            $currentSite = get_blog_details();

            foreach ($settings[self::SETTING__MULTISITE_FIELDS] as $field) {
                $data[$field] = $currentSite->{$field} ?? '';
            }
        }

        foreach ($settings[self::SETTING__BLOG_INFO] as $field) {
            $data[$field] = get_bloginfo($field);
        }

        if (count($data) < 1) {
            return null;
        }

        return ['site' => $data];
    }

    public function id(): string
    {
        return self::ID;
    }

    public function description(): string
    {
        return __(
            'Write site info into the Google Tag Manager data layer.',
            'inpsyde-google-tag-manager',
        );
    }

    public function name(): string
    {
        return __('Site info', 'inpsyde-google-tag-manager');
    }

    /**
     * @return array
     *
     * phpcs:disable Syde.Functions.FunctionLength.TooLong
     * phpcs:disable Syde.Functions.LineLength.TooLong
     */
    public function specification(): array
    {
        $multisiteNotice = is_multisite()
            ? __(
                'You\'re currently <strong>using</strong> a multisite.',
                'inpsyde-google-tag-manager',
            )
            : __(
                'You\'re currently <strong>not using</strong> a multisite.',
                'inpsyde-google-tag-manager',
            );

        $multisiteFields = [
            'label' => __('MultiSite information', 'inpsyde-google-tag-manager'),
                'name' => self::SETTING__MULTISITE_FIELDS,
                'type' => 'checkbox',
            'description' => sprintf(
            /* translators: %s is a new sentence which notifies if the user is in or not in a multisite */
                __(
                    'This data is only added when a multisite is installed. %s',
                    'inpsyde-google-tag-manager',
                ),
                $multisiteNotice,
            ),
            'choices' => [
                [
                    'label' => __('ID', 'inpsyde-google-tag-manager'),
                    'value' => 'id',
                ],
                [
                    'label' => __('Network ID', 'inpsyde-google-tag-manager'),
                    'value' => 'network_id',
                ],
                [
                    'label' => __('Blog name', 'inpsyde-google-tag-manager'),
                    'value' => 'blogname',
                ],
                [
                    'label' => __('Site url', 'inpsyde-google-tag-manager'),
                    'value' => 'siteurl',
                ],
                [
                    'label' => __('Home', 'inpsyde-google-tag-manager'),
                    'value' => 'home',
                ],
            ],
        ];

        $blogInfo = [
            'label' => __('Blog information', 'inpsyde-google-tag-manager'),
                'name' => self::SETTING__BLOG_INFO,
                'type' => 'checkbox',
            'choices' => [
                [
                    'label' => __('Name', 'inpsyde-google-tag-manager'),
                    'value' => 'name',
                ],
                [
                    'label' => __('Description', 'inpsyde-google-tag-manager'),
                    'value' => 'description',
                ],
                [
                    'label' => __('Url', 'inpsyde-google-tag-manager'),
                    'value' => 'url',
                ],
                [
                    'label' => __('Charset', 'inpsyde-google-tag-manager'),
                    'value' => 'charset',
                ],
                [
                    'label' => __('Language', 'inpsyde-google-tag-manager'),
                    'value' => 'language',
                ],
            ],
        ];

        return [$blogInfo, $multisiteFields];
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
