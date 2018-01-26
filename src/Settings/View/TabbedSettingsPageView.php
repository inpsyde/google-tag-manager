<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings\View;

use Brain\Nonces\NonceInterface;
use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\Element\CollectionElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\View\Collection;
use ChriCo\Fields\ViewFactory;
use Inpsyde\GoogleTagManager\Core\PluginConfig;
use Inpsyde\GoogleTagManager\Exception\NotFoundException;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
class TabbedSettingsPageView implements SettingsPageViewInterface
{

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
    public function __construct(PluginConfig $config, ViewFactory $view_factory = null)
    {

        $this->config       = $config;
        $this->view_factory = $view_factory ?? new ViewFactory();
    }

    /**
     * @param FormInterface  $form
     * @param NonceInterface $nonce
     * @throws NotFoundException
     */
    public function render(FormInterface $form, NonceInterface $nonce)
    {

        $url = add_query_arg([
            'page' => $this->slug(),
        ], admin_url('options-general.php'));

        if ($form->is_submitted()) {
            $this->renderNotice($form);
        }

        $sections = $this->prepareSections($form);
        ?>
        <div class="wrap">
            <h2 class="settings__headline"><?= esc_html($this->name()) ?></h2>
            <form method="post" action="<?= esc_url($url) ?>" class="inpsyde-form" id="inpsyde-form">
                <div id="inpsyde-tabs" class="inpsyde-tabs">
                    <ul class="inpsyde-tab__navigation wp-clearfix">
                        <?= array_reduce($sections, [$this, 'renderTabNavItem'], '') /* xss ok */ ?>
                    </ul>
                    <?php array_walk($sections, [$this, 'renderTabContent']) /* xss ok */ ?>
                    <p class="submit clear">
                        <?= \Brain\Nonces\formField($nonce) /* xss ok */ ?>
                        <input type="submit"
                               name="submit"
                               id="submit"
                               class="inpsyde-form-field__submit"
                               value="<?= esc_attr__('Save Changes', 'inpsyde-google-tag-manager') ?>"
                        />
                    </p>
                    <img
                            src="<?= esc_url($this->config->get('assets.img.url') . 'inpsyde.png'); ?>"
                            srcset="<?= esc_url($this->config->get('assets.img.url') . 'inpsyde.svg'); ?>"
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
     * {@inheritdoc}
     */
    public function slug(): string
    {

        return $this->config->get('plugin.textdomain');
    }

    /**
     * Internal function to render success or error notice.
     *
     * @param FormInterface $form
     *
     * @return void
     */
    public function renderNotice(FormInterface $form)
    {

        $class   = 'error';
        $message = esc_html__(
            'New settings stored, but there are some errors. Please scroll down to have a look.',
            'inpsyde-google-tag-manager'
        );

        if ($form->is_valid()) {
            $class   = 'updated';
            $message = esc_html__('New settings successfully stored.', 'inpsyde-google-tag-manager');
        }

        printf(
            '<div class="%1$s"><p><strong>%2$s</strong></p></div>',
            esc_attr($class),
            $message
        );
    }

    /**
     * Internal function which moves all collections to a "tab" and all other elements into a "general"-tab.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    private function prepareSections(FormInterface $form): array
    {

        $sections = [];
        $default  = [];
        /** @var CollectionElementInterface $form */
        foreach ($form->get_elements() as $element) {
            if ($element instanceof CollectionElement) {
                $sections[ $element->get_name() ] = [
                    'id'          => $element->get_name(),
                    'title'       => $element->get_label(),
                    'description' => $element->get_description(),
                    'elements'    => [$element],
                ];
                continue;
            }
            // if not a collection field, add it to the default section.
            $default[] = $element;
        }

        if (count($default) > 0) {
            $sections[ 'general' ] = [
                'id'          => 'general',
                'title'       => __('General settings', 'inpsyde-google-tag-manager'),
                'description' => '',
                'elements'    => $default,
            ];
        }

        return $sections;
    }

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {

        return __('Google Tag Manager', 'inpsyde-google-tag-manager');
    }

    /**
     * Internal function to render the tab navigation.
     *
     * @param string $html
     * @param array  $section
     *
     * @return string
     */
    public function renderTabNavItem(string $html, array $section): string
    {

        $html .= sprintf(
            '<li class="inpsyde-tab__navigation-item"><a href="#%1$s">%2$s</a></li>',
            esc_attr('tab--' . $section[ 'id' ]),
            esc_html($section[ 'title' ])
        );

        return $html;
    }

    /**
     * Internal function to render the tab content by a given section.
     *
     * @param array $section
     */
    public function renderTabContent(array $section)
    {

        if (count($section[ 'elements' ]) < 1) {
            return;
        }

        ?>
        <div id="tab--<?= esc_attr($section[ 'id' ]) ?>" class="inpsyde-tab__content">
            <h3 class="screen-reader-text"><?= esc_html($section[ 'title' ]) ?></h3>
            <?php
            if (isset($section[ 'description' ])) {
                echo '<p>' . $section[ 'description' ] . '</p>';
            } ?>
            <?= array_reduce($section[ 'elements' ], [$this, 'renderElement'], '') ?>
        </div>

        <?php
    }

    /**
     * Internal function to render a single element row.
     *
     * @param string           $html
     * @param ElementInterface $element
     *
     * @return string
     */
    private function renderElement(string $html, ElementInterface $element): string
    {

        $html .= $this->view_factory->create(Collection::class)->render($element);

        return $html;
    }
}