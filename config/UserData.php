<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\User\UserDataCollector;

$enabled = [
	'label'      => __( 'Enable/disable user data', 'inpsyde-google-tag-manager' ),
	'attributes' => [
		'name' => UserDataCollector::SETTING__ENABLED,
		'type' => 'select',
	],
	'choices'    => [
		DataCollectorInterface::VALUE_ENABLED  => __( 'Enabled', 'inpsyde-google-tag-manager' ),
		DataCollectorInterface::VALUE_DISABLED => __( 'Disabled', 'inpsyde-google-tag-manager' ),
	],
];

$visitor = [
	'label'       => __( 'Visitor role', 'inpsyde-google-tag-manager' ),
	'description' => __(
		'Which role should be displayed in dataLayer for not logged in users? Leave blank for no role.',
		'inpsyde-google-tag-manager'
	),
	'attributes'  => [
		'name'  => UserDataCollector::SETTING__VISITOR_ROLE,
		'type'  => 'text',
		'value' => 'visitor',
	],
];

$fields = [
	'label'      => __( 'Fields used in dataLayer', 'inpsyde-google-tag-manager' ),
	'attributes' => [
		'name' => UserDataCollector::SETTING__FIELDS,
		'type' => 'checkbox',
	],
	'choices'    => [
		'ID'               => __( 'ID', 'inpsyde-google-tag-manager' ),
		'role'             => __( 'Role', 'inpsyde-google-tag-manager' ),
		'nickname'         => __( 'Nickname', 'inpsyde-google-tag-manager' ),
		'user_description' => __( 'Description', 'inpsyde-google-tag-manager' ),
		'first_name'       => __( 'First name', 'inpsyde-google-tag-manager' ),
		'last_name'        => __( 'Last name', 'inpsyde-google-tag-manager' ),
		'user_email'       => __( 'E-Mail', 'inpsyde-google-tag-manager' ),
		'url'              => __( 'Url', 'inpsyde-google-tag-manager' ),
	],
];

return [
	'label'      => __( 'User', 'inpsyde-google-tag-manager' ),
	'attributes' => [
		'name' => UserDataCollector::SETTING__KEY,
		'type' => 'collection',
	],
	'elements'   => [ $enabled, $visitor, $fields, ],
];
