<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Settings\Auth;

use Brain\Monkey\Functions;
use Brain\Nonces\NonceInterface;
use ChriCo\Fields\Element\FormInterface;
use Inpsyde\GoogleTagManager\Settings\View\SettingsPageViewInterface;
use Inpsyde\GoogleTagManager\Settings\View\TabbedSettingsPageView;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Inpsyde\Modularity\Properties\PluginProperties;
use Mockery;

class TabbedSettingsPageViewTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {

        $expected_textdomain = 'foo';

        $config = Mockery::mock(PluginProperties::class);
        $config->shouldReceive('textDomain')->andReturn($expected_textdomain);

        $testee = new TabbedSettingsPageView($config);

        Functions\expect('__')->andReturnFirstArg();

        static::assertInstanceOf(SettingsPageViewInterface::class, $testee);
        static::assertSame('Google Tag Manager', $testee->name());
        static::assertSame($expected_textdomain, $testee->slug());
    }

    /**
     * @test
     */
    public function testRender(): void
    {

        Functions\stubs(['__', '_e', 'esc_url', 'esc_attr', 'admin_url', 'esc_html', 'esc_html__', 'esc_attr__']);

        Functions\expect('add_query_arg')->once()->andReturn('');

        $config = Mockery::mock(PluginProperties::class);
        $config->expects('textDomain')->andReturn('domain');
        $config->expects('baseUrl')->andReturn('https://example.com/');

        $form = Mockery::mock(FormInterface::class);
        $form->shouldReceive('elements')->once()->andReturn([]);
        $form->shouldReceive('isSubmitted')->once()->andReturn(true);
        $form->shouldReceive('isValid')->once()->andReturn(true);

        $nonce = Mockery::mock(NonceInterface::class);
        $nonce->shouldReceive('action')->once()->andReturn('');

        ob_start();
        (new TabbedSettingsPageView($config))->render($form, $nonce);
        $output = ob_get_clean();

        static::assertStringContainsString('<div class="wrap">', $output);
        static::assertStringContainsString('<form', $output);
        static::assertStringContainsString('method="post"', $output);
        static::assertStringContainsString('</form>', $output);
        static::assertStringContainsString('</div>', $output);
    }

    /**
     * @test
     * @dataProvider provideRenderNotice
     */
    public function testRenderNotice($valid, $expected): void
    {

        Functions\stubs(['__', 'esc_html', 'esc_attr']);
        Functions\when('filter_input')->justReturn('POST');

        $config = Mockery::mock(PluginProperties::class);
        $form   = Mockery::mock(FormInterface::class);
        $form->shouldReceive('isValid')->once()->andReturn($valid);

        $testee = new TabbedSettingsPageView($config);

        ob_start();
        $testee->renderNotice($form);
        $output = ob_get_clean();

        static::assertStringContainsString($expected, $output);
    }

    /**
     * @return array
     */
    public function provideRenderNotice(): array
    {

        return [
            'valid form'   => [true, 'class="updated"'],
            'invalid form' => [false, 'class="error"']
        ];
    }

    /**
     * @test
     */
    public function testRenderTabNavItem(): void
    {

        $expected_id    = 'foo';
        $expected_title = 'bar';

        Functions\stubs(['esc_attr', 'esc_html']);

        $config = Mockery::mock(PluginProperties::class);

        $testee = new TabbedSettingsPageView($config);

        $output = $testee->renderTabNavItem('', [
            'id'    => $expected_id,
            'title' => $expected_title
        ]);

        static::assertStringContainsString('<li class="', $output);
        static::assertStringContainsString($expected_id, $output);
        static::assertStringContainsString($expected_title, $output);
        static::assertStringContainsString('</li>', $output);
    }

    /**
     * @test
     */
    public function testRenderTabContent(): void
    {

        $config = Mockery::mock(PluginProperties::class);
        $testee = new TabbedSettingsPageView($config);

        static::assertEmpty($testee->renderTabContent(['elements' => []]));
    }

}
