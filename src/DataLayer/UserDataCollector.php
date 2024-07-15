<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer\User
 */
class UserDataCollector implements DataCollectorInterface, SettingsSpecAwareInterface
{

    public const ID = 'userData';

    public const SETTING__VISITOR_ROLE = 'visitor_role';
    public const SETTING__FIELDS = 'fields';

    /**
     * @var array
     */
    private array $settings = [
        self::SETTING__VISITOR_ROLE => '',
        self::SETTING__FIELDS => [],
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
        return __('User', 'inpsyde-google-tag-manager');
    }

    public function description(): string
    {
        return __(
            'Write user data into the Google Tag Manager data layer.',
            'inpsyde-google-tag-manager'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function data(): array
    {
        $isLoggedIn = is_user_logged_in();
        $data = [];

        if ($isLoggedIn) {
            $currentUser = wp_get_current_user();
            foreach ($this->fields() as $field) {
                $data[$field] = $currentUser->{$field} ?? '';
            }
        }

        // only change the role, if the user has marked this field in backend.
        $role = $this->role();
        if ($role !== '') {
            $data['role'] = $role;
        }

        $data['isLoggedIn'] = (bool) $isLoggedIn;

        return ['user' => $data];
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return $this->settings[self::SETTING__FIELDS];
    }

    /**
     * @return string
     */
    public function role(): string
    {
        if (!is_user_logged_in()) {
            return $this->visitorRole();
        }

        $currentUser = wp_get_current_user();
        if (isset($currentUser->roles[0])) {
            return $currentUser->roles[0];
        }

        return '';
    }

    /**
     * @return string
     */
    public function visitorRole(): string
    {
        return $this->settings[self::SETTING__VISITOR_ROLE];
    }

    /**
     * @return array
     */
    // phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
    // phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
    public function settingsSpec(): array
    {
        $visitor = [
            'label' => __('Visitor role', 'inpsyde-google-tag-manager'),
            'description' => __(
                'Which role should be displayed in dataLayer for not logged in users? Leave blank for no role.',
                'inpsyde-google-tag-manager'
            ),
            'attributes' => [
                'name' => self::SETTING__VISITOR_ROLE,
                'type' => 'text',
                'value' => 'visitor',
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
                'role' => __('Role', 'inpsyde-google-tag-manager'),
                'nickname' => __('Nickname', 'inpsyde-google-tag-manager'),
                'user_description' => __('Description', 'inpsyde-google-tag-manager'),
                'first_name' => __('First name', 'inpsyde-google-tag-manager'),
                'last_name' => __('Last name', 'inpsyde-google-tag-manager'),
                'user_email' => __('E-Mail', 'inpsyde-google-tag-manager'),
                'url' => __('Url', 'inpsyde-google-tag-manager'),
            ],
        ];

        return [$visitor, $fields];
    }
}
