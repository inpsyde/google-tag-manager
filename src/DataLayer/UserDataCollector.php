<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\User
 */
class UserDataCollector implements DataCollector, SettingsSpecification
{
    public const ID = 'userData';

    public const SETTING__VISITOR_ROLE = 'visitor_role';
    public const SETTING__FIELDS = 'fields';

    /**
     * @var array
     */
    private const DEFAULTS = [
        self::SETTING__VISITOR_ROLE => 'visitor',
        self::SETTING__FIELDS => [],
    ];

    protected function __construct()
    {
    }

    public static function new(): UserDataCollector
    {
        return new self();
    }

    public function id(): string
    {
        return self::ID;
    }

    public function name(): string
    {
        return __('User', 'inpsyde-google-tag-manager');
    }

    public function description(): string
    {
        return __(
            'Write user data into the Google Tag Manager data layer.',
            'inpsyde-google-tag-manager',
        );
    }

    public function data(array $settings): ?array
    {
        $isLoggedIn = is_user_logged_in();

        if (!$isLoggedIn) {
            return [
                'user' => [
                    'role' => $settings[self::SETTING__VISITOR_ROLE] ?? 'visitor',
                    'isLoggedIn' => $isLoggedIn,
                ],
            ];
        }

        $data = [];
        $currentUser = wp_get_current_user();
        foreach ($settings[self::SETTING__FIELDS] as $field) {
            if ($field === 'role') {
                $data[$field] = $currentUser->roles[0] ?? '';
                continue;
            }
            $data[$field] = $currentUser->{$field} ?? '';
        }
        $data['isLoggedIn'] = $isLoggedIn;

        return ['user' => $data];
    }

    /**
     * @return array
     *
     * phpcs:disable Syde.Functions.FunctionLength.TooLong
     */
    public function specification(): array
    {
        $visitor = [
            'label' => __('Visitor role', 'inpsyde-google-tag-manager'),
            'description' => __(
                'Which role should be displayed in dataLayer for not logged in users? Default: "visitor".',
                'inpsyde-google-tag-manager',
            ),
            'name' => self::SETTING__VISITOR_ROLE,
            'type' => 'text',
        ];

        $fields = [
            'label' => __('Fields used in dataLayer', 'inpsyde-google-tag-manager'),
            'name' => self::SETTING__FIELDS,
            'type' => 'checkbox',
            'choices' => [
                [
                    'label' => __('ID', 'inpsyde-google-tag-manager'),
                    'value' => 'ID',
                ],
                [
                    'label' => __('Role', 'inpsyde-google-tag-manager'),
                    'value' => 'role',
                ],
                [
                    'label' => __('Nickname', 'inpsyde-google-tag-manager'),
                    'value' => 'nickname',
                ],
                [
                    'label' => __('Description', 'inpsyde-google-tag-manager'),
                    'value' => 'user_description',
                ],
                [
                    'label' => __('First name', 'inpsyde-google-tag-manager'),
                    'value' => 'first_name',
                ],
                [
                    'label' => __('Last name', 'inpsyde-google-tag-manager'),
                    'value' => 'last_name',
                ],
                [
                    'label' => __('E-Mail', 'inpsyde-google-tag-manager'),
                    'value' => 'user_email',
                ],
                [
                    'label' => __('Url', 'inpsyde-google-tag-manager'),
                    'value' => 'url',
                ],
            ],
        ];

        return [$visitor, $fields];
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
