<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings\View;

use Brain\Nonces\NonceInterface;
use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\Element\CollectionElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\FormInterface;
use Inpsyde\Modularity\Properties\PluginProperties;

use function ChriCo\Fields\renderElement;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
class TabbedSettingsPageView implements SettingsPageViewInterface
{
    public function __construct(protected PluginProperties $properties)
    {
    }

    /**
     * @param FormInterface $form
     * @param NonceInterface $nonce
     */
    // phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    public function render(FormInterface $form, NonceInterface $nonce)
    {
        $url = add_query_arg(
            [
                'page' => $this->slug(),
            ],
            admin_url('options-general.php')
        );

        if ($form->isSubmitted()) {
            $this->renderNotice($form);
        }

        $assetUrl = $this->properties->baseUrl() . 'assets/';
        $sections = $this->prepareSections($form);
        ?>
        <div class="wrap">
            <h2 class="settings__headline"><?= esc_html($this->name()) ?></h2>
            <form method="post" action="<?= esc_url($url) ?>" class="inpsyde-form" id="inpsyde-form">
                <div id="inpsyde-tabs" class="inpsyde-tabs">
                    <ul class="inpsyde-tab__navigation wp-clearfix">
                        <?= array_reduce($sections, [$this, 'renderTabNavItem'], '') ?>
                    </ul>
                    <?php array_walk($sections, [$this, 'renderTabContent']) ?>
                    <p class="submit clear">
                        <?= \Brain\Nonces\formField($nonce) ?>
                        <input type="submit"
                            name="submit"
                            id="submit"
                            class="inpsyde-form-field__submit"
                            value="<?= esc_attr__('Save Changes', 'inpsyde-google-tag-manager') ?>"
                        />
                    </p>
                    <img
                        src="<?= esc_url($assetUrl . 'images/inpsyde.png'); ?>"
                        srcset="<?= esc_url($assetUrl . 'images/inpsyde.svg'); ?>"
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
    // phpcs:enable

    /**
     * @return string
     */
    public function slug(): string
    {
        return $this->properties->textDomain();
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
        $class = 'error';
        $message = __(
            'New settings stored, but there are some errors. Please scroll down to have a look.',
            'inpsyde-google-tag-manager'
        );

        if ($form->isValid()) {
            $class = 'updated';
            $message = __('New settings successfully stored.', 'inpsyde-google-tag-manager');
        }

        printf(
            '<div class="%1$s"><p><strong>%2$s</strong></p></div>',
            esc_attr($class),
            esc_html($message)
        );
    }

    /**
     * Internal function which moves all collections to a "tab"
     * and all other elements into a "general"-tab.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    private function prepareSections(FormInterface $form): array
    {
        $sections = [];
        $default = [];
        /** @var CollectionElementInterface $form */
        foreach ($form->elements() as $element) {
            if ($element instanceof CollectionElement) {
                $sections[$element->name()] = [
                    'id' => $element->name(),
                    'title' => $element->label(),
                    'description' => $element->description(),
                    'elements' => [$element],
                ];
                continue;
            }
            // if not a collection field, add it to the default section.
            $default[] = $element;
        }

        if (count($default) > 0) {
            $sections['general'] = [
                'id' => 'general',
                'title' => __('General settings', 'inpsyde-google-tag-manager'),
                'description' => '',
                'elements' => $default,
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
     * @param array $section
     *
     * @return string
     */
    public function renderTabNavItem(string $html, array $section): string
    {
        $html .= sprintf(
            '<li class="inpsyde-tab__navigation-item"><a href="#%1$s">%2$s</a></li>',
            esc_attr('tab--' . $section['id']),
            esc_html($section['title'])
        );

        return $html;
    }

    /**
     * Internal function to render the tab content by a given section.
     *
     * @param array $section
     */
    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    public function renderTabContent(array $section)
    {
        if (count($section['elements']) < 1) {
            return;
        }

        ?>
        <div id="tab--<?= esc_attr($section['id']) ?>" class="inpsyde-tab__content">
            <h3 class="screen-reader-text"><?= esc_html($section['title']) ?></h3>
            <?php
            if (isset($section['description'])) {
                echo '<p>' . $section['description'] . '</p>';
            } ?>
            <?= array_reduce($section['elements'], [$this, 'renderElement'], ''); ?>
        </div>

        <?php
    }
    // phpcs:enable

    /**
     * Internal function to render a single element row.
     *
     * @param string $html
     * @param ElementInterface $element
     *
     * @return string
     * @throws \ChriCo\Fields\Exception\UnknownTypeException
     */
    private function renderElement(string $html, ElementInterface $element): string
    {
        $html .= renderElement($element);

        return $html;
    }
}