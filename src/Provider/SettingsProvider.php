<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\Modularity\Package;
use Inpsyde\Modularity\Properties\PluginProperties;
use Psr\Container\ContainerInterface;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
final class SettingsProvider implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            // phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
            SettingsRepository::class => static function (ContainerInterface $container): SettingsRepository {
                /** @var PluginProperties $properties */
                $properties = $container->get(Package::PROPERTIES);

                return SettingsRepository::new($properties->textDomain());
            },
            SettingsPage::class => static function (ContainerInterface $container): SettingsPage {
                /** @var PluginProperties $properties */
                $properties = $container->get(Package::PROPERTIES);

                return SettingsPage::new($properties);
            },
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        if (!is_admin()) {
            return false;
        }

        add_action(
            'admin_menu',
            static function () use ($container) {

                /** @var PluginProperties $properties */
                $properties = $container->get(Package::PROPERTIES);

                add_options_page(
                    __('Google Tag Manager', 'inpsyde-google-tag-manager'),
                    __('Google Tag Manager', 'inpsyde-google-tag-manager'),
                    'manage_options',
                    $properties->baseName(),
                    static function () use ($properties): void {
                        $assetUrl = $properties->baseUrl() . 'assets/';

                        $link = add_query_arg(
                            [
                                'utm_source' => site_url(),
                                'utm_medium' => 'logo',
                                'utm_campaign' => 'plugin',
                                'utm_id' => $properties->baseName(),
                            ],
                            'https://syde.com'
                        );
                        ?>
                        <div class="wrap">
                            <h2 class="settings__headline">
                                <?= esc_html(__('Google Tag Manager', 'inpsyde-google-tag-manager')) ?>
                                by
                                <a href="<?= esc_url($link) ?>">
                                    <img
                                        src="<?= esc_url($assetUrl . 'images/syde.svg'); ?>"
                                        alt="Syde GmbH"
                                        width="150"
                                        height="47"
                                        class="syde-logo__image"
                                    />
                                </a>
                            </h2>
                            <div class="settings__wrapper">
                                <div class="settings__content"></div>
                            </div>
                        </div>
                        <?php
                    }
                );
            }
        );

        return true;
    }
}
