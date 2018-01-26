<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Settings\Auth;

use Brain\Monkey\Functions;
use Brain\Nonces\NonceInterface;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\ViewFactory;
use Inpsyde\GoogleTagManager\Core\PluginConfig;
use Inpsyde\GoogleTagManager\Settings\View\SettingsPageViewInterface;
use Inpsyde\GoogleTagManager\Settings\View\TabbedSettingsPageView;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class TabbedSettingsPageViewTest extends AbstractTestCase
{

    public function test_basic()
    {

        $expected_textdomain = 'foo';

        $config = Mockery::mock(PluginConfig::class);
        $config->shouldReceive('get')->with('plugin.textdomain')->andReturn($expected_textdomain);

        $factory = Mockery::mock(ViewFactory::class);

        $testee = new TabbedSettingsPageView($config, $factory);

        Functions\expect('__')->andReturnFirstArg();

        static::assertInstanceOf(SettingsPageViewInterface::class, $testee);
        static::assertSame('Google Tag Manager', $testee->name());
        static::assertSame($expected_textdomain, $testee->slug());
    }

    public function test_render()
    {

        Functions\stubs(['__', '_e', 'esc_url', 'esc_attr', 'admin_url', 'esc_html', 'esc_html__', 'esc_attr__']);

        Functions\expect('add_query_arg')->once()->andReturn('');

        $config = Mockery::mock(PluginConfig::class);
        $config->shouldReceive('get')->andReturnUsing(function ($args) {

            return $args[ 0 ];
        });

        $factory = Mockery::mock(ViewFactory::class);

        $form = Mockery::mock(FormInterface::class);
        $form->shouldReceive('get_elements')->once()->andReturn([]);
        $form->shouldReceive('is_submitted')->once()->andReturn(true);
        $form->shouldReceive('is_valid')->once()->andReturn(true);

        $nonce = Mockery::mock(NonceInterface::class);
        $nonce->shouldReceive('action')->once()->andReturn('');

        ob_start();
        (new TabbedSettingsPageView($config, $factory))->render($form, $nonce);
        $output = ob_get_clean();

        static::assertContains('<div class="wrap">', $output);
        static::assertContains('<form', $output);
        static::assertContains('method="post"', $output);
        static::assertContains('</form>', $output);
        static::assertContains('</div>', $output);
    }

    /**
     * @dataProvider provide_render_notice
     */
    public function test_render_notice($valid, $expected)
    {

        Functions\stubs(['__', 'esc_html__', 'esc_attr']);
        Functions\when('filter_input')->justReturn('POST');

        $config = Mockery::mock(PluginConfig::class);
        $form   = Mockery::mock(FormInterface::class);
        $form->shouldReceive('is_valid')->once()->andReturn($valid);

        $testee = new TabbedSettingsPageView($config);

        ob_start();
        $testee->renderNotice($form);
        $output = ob_get_clean();

        static::assertContains($expected, $output);
    }

    /**
     * @return array
     */
    public function provide_render_notice(): array
    {

        return [
            'valid form'   => [true, 'class="updated"'],
            'invalid form' => [false, 'class="error"']
        ];
    }

    public function test_render_tab_nav_item()
    {

        $expected_id    = 'foo';
        $expected_title = 'bar';

        Functions\stubs(['esc_attr', 'esc_html']);

        $config = Mockery::mock(PluginConfig::class);

        $testee = new TabbedSettingsPageView($config);

        $output = $testee->renderTabNavItem('', [
            'id'    => $expected_id,
            'title' => $expected_title
        ]);

        static::assertContains('<li class="', $output);
        static::assertContains($expected_id, $output);
        static::assertContains($expected_title, $output);
        static::assertContains('</li>', $output);
    }

    public function test_render_tab_content()
    {

        $config = Mockery::mock(PluginConfig::class);
        $testee = new TabbedSettingsPageView($config);

        static::assertEmpty($testee->renderTabContent(['elements' => []]));
    }

}
