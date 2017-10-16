<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\Site\SiteInfoDataCollector;

$enabled = [
	'label'      => __( 'Enable/disable site info data', 'inpsyde-google-tag-manager' ),
	'attributes' => [
		'name' => SiteInfoDataCollector::SETTING__ENABLED,
		'type' => 'select'
	],
	'choices'    => [
		DataCollectorInterface::VALUE_ENABLED  => __( 'Enabled', 'inpsyde-google-tag-manager' ),
		DataCollectorInterface::VALUE_DISABLED => __( 'Disabled', 'inpsyde-google-tag-manager' ),
	]
];

$ms_fields = [
	'label'       => __( 'MultiSite information', 'inpsyde-google-tag-manager' ),
	'attributes'  => [
		'name' => SiteInfoDataCollector::SETTING__MULTISITE_FIELDS,
		'type' => 'checkbox'
	],
	'choices'     => [
		'id'         => __( 'ID', 'inpsyde-google-tag-manager' ),
		'network_id' => __( 'Network ID', 'inpsyde-google-tag-manager' ),
		'blogname'   => __( 'Blog name', 'inpsyde-google-tag-manager' ),
		'siteurl'    => __( 'Site url', 'inpsyde-google-tag-manager' ),
		'home'       => __( 'Home', 'inpsyde-google-tag-manager' ),
	],
	'description' => sprintf(
		__(
			'This data is only added when a multisite is installed. You\'re currently <strong>%s</strong> a Multisite.',
			'inpsyde-google-tag-manager'
		),
		is_multisite()
			? __( 'using', 'inpsyde-google-tag-manager' )
			: __( 'not using', 'inpsyde-google-tag-manager' )
	)
];

$blog_info = [
	'label'      => __( 'Blog information', 'inpsyde-google-tag-manager' ),
	'attributes' => [
		'name' => SiteInfoDataCollector::SETTING__BLOG_INFO,
		'type' => 'checkbox'
	],
	'choices'    => [
		'name'        => __( 'Name', 'inpsyde-google-tag-manager' ),
		'description' => __( 'Description', 'inpsyde-google-tag-manager' ),
		'url'         => __( 'Url', 'inpsyde-google-tag-manager' ),
		'charset'     => __( 'Charset', 'inpsyde-google-tag-manager' ),
		'language'    => __( 'Language', 'inpsyde-google-tag-manager' )
	]
];

return [
	'label'      => __( 'Site info', 'inpsyde-google-tag-manager' ),
	'attributes' => [
		'name' => SiteInfoDataCollector::SETTING__KEY,
		'type' => 'collection'
	],
	'elements'   => [ $enabled, $blog_info, $ms_fields ]
];
