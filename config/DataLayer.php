<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

use Inpsyde\Filter\ArrayValue;
use Inpsyde\Filter\WordPress\StripTags;
use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\Validator\DataValidator;
use Inpsyde\Validator\RegEx;

$gtm_id = [
	'label'      => __( 'Google Tag Manager ID', 'inpsyde-google-tag-manager' ),
	'attributes' => [
		'name' => DataLayer::SETTING__GTM_ID,
		'type' => 'text',
	],
];

$noscript = [
	'label'       => __( 'Auto insert noscript in body', 'inpsyde-google-tag-manager' ),
	'description' => __(
		'If enabled, the Plugin tries automatically to insert the <code>&lt;noscript&gt</code>-tag after the <code>&lt;body&gt;</code>-tag</code>. This may can cause problems with other plugins, so to be safe, disable this feature and add to your theme after <code>&lt;body&gt;</code> following: <pre><code>&lt;?php do_action( "inpsyde-google-tag-manager.noscript" ); ?&gt;</code></pre>',
		'inpsyde-google-tag-manager'
	),
	'attributes'  => [
		'name' => DataLayer::SETTING__AUTO_INSERT_NOSCRIPT,
		'type' => 'select',
	],
	'choices'     => [
		DataCollectorInterface::VALUE_ENABLED  => __( 'Enable', 'inpsyde-google-tag-manager' ),
		DataCollectorInterface::VALUE_DISABLED => __( 'Disable', 'inpsyde-google-tag-manager' ),
	],
];

$data_layer = [
	'label'       => __( 'dataLayer name', 'inpsyde-google-tag-manager' ),
	'description' => __(
		'In some cases you have to rename the <var>dataLayer</var>-variable. Default: dataLayer',
		'inpsyde-google-tag-manager'
	),
	'attributes'  => [
		'name' => DataLayer::SETTING__DATALAYER_NAME,
		'type' => 'text',
	],
];

return [
	'label'       => __( 'DataLayer', 'inpsyde-google-tag-manager' ),
	'description' => __(
		'More information about Google Tag Manager can be found in <a href="https://developers.google.com/tag-manager/">Google Tag Manager Help Center</a>.',
		'inpsyde-google-tag-manager'
	),
	'attributes'  => [
		'name' => DataLayer::SETTING__KEY,
		'type' => 'collection',
	],
	'elements'    => [ $gtm_id, $noscript, $data_layer, ],
	'validators'  => [
		( new DataValidator() )->add_validator_by_key(
			new RegEx( [ 'pattern' => '/^GTM-[A-Z0-9]+$/' ] ), DataLayer::SETTING__GTM_ID
		),
	],
	'filters'     => [
		( new ArrayValue() )->add_filter( new StripTags() ),
	],
];