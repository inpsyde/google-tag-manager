<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings\View;

use Brain\Nonces\NonceInterface;
use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\Element\CollectionElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\View\Collection;
use ChriCo\Fields\ViewFactory;
use Inpsyde\GoogleTagManager\Core\PluginConfig;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
class TabbedSettingsPageView implements SettingsPageViewInterface {

	/**
	 * @var PluginConfig
	 */
	private $config;

	/**
	 * @var ViewFactory
	 */
	private $view_factory;

	/**
	 * SettingsPageView constructor.
	 *
	 * @param PluginConfig $config
	 * @param ViewFactory  $view_factory
	 */
	public function __construct( PluginConfig $config, ViewFactory $view_factory = NULL ) {

		$this->config       = $config;
		$this->view_factory = $view_factory ?? new ViewFactory();
	}

	/**
	 * {@inheritdoc}
	 */
	public function name(): string {

		return __( 'Google Tag Manager', 'inpsyde-google-tag-manager' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function slug(): string {

		return $this->config->get( 'plugin.textdomain' );
	}

	/**
	 * @param FormInterface  $form
	 * @param NonceInterface $nonce
	 * @param bool           $send
	 */
	public function render( FormInterface $form, NonceInterface $nonce, $send = FALSE ) {

		$url = add_query_arg(
			[
				'page' => $this->slug(),
			],
			admin_url( 'options-general.php' )
		);

		if ( $send ) {
			$this->render_notice( $form );
		}

		$sections = $this->prepare_sections( $form );
		?>
		<div class="wrap">
			<h2 class="settings__headline"><?= esc_html( $this->name() ) ?></h2>
			<form method="post" action="<?= esc_url( $url ) ?>" class="inpsyde-form" id="inpsyde-form">
				<div id="inpsyde-tabs" class="inpsyde-tabs">
					<ul class="inpsyde-tab__navigation wp-clearfix">
						<?= array_reduce( $sections, [ $this, 'render_tab_nav_item' ], '' ) /* xss ok */ ?>
					</ul>
					<?= array_reduce( $sections, [ $this, 'render_tab_content' ], '' ) /* xss ok */ ?>
					<p class="submit clear">
						<?= \Brain\Nonces\formField( $nonce ) /* xss ok */ ?>
						<input type="submit"
							name="submit"
							id="submit"
							class="inpsyde-form-field__submit"
							value="<?= esc_attr__( 'Save Changes', 'inpsyde-google-tag-manager' ) ?>"
						/>
					</p>
					<img
						src="<?= esc_url( $this->config->get( 'assets.img.url' ) . 'inpsyde.png' ); ?>"
						srcset="<?= esc_url( $this->config->get( 'assets.img.url' ) . 'inpsyde.svg' ); ?>"
						alt="Inpsyde GmbH"
						width="150"
						height="47"
						class="inpsyde-logo__image"
					/>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Internal function which moves all collections to a "tab" and all other elements into a "general"-tab.
	 *
	 * @param FormInterface $form
	 *
	 * @return array
	 */
	private function prepare_sections( FormInterface $form ): array {

		$sections = [];
		$default  = [];
		/** @var CollectionElementInterface $form */
		foreach ( $form->get_elements() as $element ) {
			if ( $element instanceof CollectionElement ) {
				$sections[ $element->get_name() ] = [
					'id'          => $element->get_name(),
					'title'       => $element->get_label(),
					'description' => $element->get_description(),
					'elements'    => [ $element ],
				];
			} else {
				$default[] = $element;
			}
		}

		if ( count( $default ) > 0 ) {
			$sections[ 'general' ] = [
				'id'          => 'general',
				'title'       => __( 'General settings', 'inpsyde-google-tag-manager' ),
				'description' => '',
				'elements'    => $default,
			];
		}

		return $sections;
	}

	/**
	 * Internal function to render success or error notice.
	 *
	 * @param FormInterface $form
	 */
	public function render_notice( FormInterface $form ) {

		if ( $form->is_valid() ) {

			printf(
				'<div class="updated"><p><strong>%s</strong></p></div>',
				esc_html__( 'New settings successfully stored.', 'inpsyde-google-tag-manager' )
			);

		} else {

			printf(
				'<div class="error"><p><strong>%s</strong></p></div>',
				esc_html__(
					'New settings stored, but there are some errors. Please scroll down to have a look.',
					'inpsyde-google-tag-manager'
				)
			);
		}
	}

	/**
	 * Internal function to render the tab navigation.
	 *
	 * @param string $html
	 * @param array  $section
	 *
	 * @return string
	 */
	public function render_tab_nav_item( string $html, array $section ): string {

		$html .= sprintf(
			'<li class="inpsyde-tab__navigation-item"><a href="#%1$s">%2$s</a></li>',
			esc_attr( 'tab--' . $section[ 'id' ] ),
			esc_html( $section[ 'title' ] )
		);

		return $html;

	}

	/**
	 * Internal function to render the tab content by a given section.
	 *
	 * @param string $html
	 * @param array  $section
	 *
	 * @return string
	 */
	public function render_tab_content( string $html, array $section ): string {

		if ( count( $section[ 'elements' ] ) < 1 ) {

			return $html;
		}

		$title = sprintf(
			'<h3 class="screen-reader-text">%s</h3>',
			esc_html( $section[ 'title' ] )
		);

		$description = $section[ 'description' ] !== ''
			? sprintf( '<p>%s</p>', $section[ 'description' ] )
			: '';

		$elements = array_reduce(
			$section[ 'elements' ],
			[ $this, 'render_element' ],
			''
		);

		$html .= sprintf(
			'<div id="tab--%1$s" class="inpsyde-tab__content">%2$s %3$s %4$s</div>',
			esc_attr( $section[ 'id' ] ),
			$title,
			$description,
			$elements
		);

		return $html;
	}

	/**
	 * Internal function to render a single element row.
	 *
	 * @param string           $html
	 * @param ElementInterface $element
	 *
	 * @return string
	 */
	private function render_element( string $html, ElementInterface $element ): string {

		$html .= $this->view_factory->create( Collection::class )
			->render( $element );

		return $html;
	}

}
