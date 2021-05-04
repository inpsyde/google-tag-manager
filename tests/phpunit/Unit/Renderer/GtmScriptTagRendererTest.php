<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Renderer;

use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent;
use Inpsyde\GoogleTagManager\Event\LogEvent;
use Inpsyde\GoogleTagManager\Renderer\GtmScriptTagRenderer;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class GtmScriptTagRendererTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $testee = new GtmScriptTagRenderer(Mockery::mock(DataLayer::class));
        static::assertInstanceOf(GtmScriptTagRenderer::class, $testee);
    }

    /**
     * @test
     */
    public function testRender(): void
    {
        $expected_id = 'GTM-123456';
        $expected_name = 'foo';

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('id')
            ->once()
            ->andReturn($expected_id);
        $dataLayer->shouldReceive('name')
            ->once()
            ->andReturn($expected_name);

        Functions\expect('esc_js')
            ->twice()
            ->andReturnFirstArg();

        Filters\expectApplied(GtmScriptTagRendererEvent::FILTER_SCRIPT)
            ->once()
            ->andReturnFirstArg();

        Filters\expectApplied(GtmScriptTagRendererEvent::FILTER_SCRIPT_ATTRIBUTES)
            ->once()
            ->andReturn([]);

        Actions\expectDone(GtmScriptTagRendererEvent::ACTION_BEFORE_SCRIPT)
            ->once()
            ->with(DataLayer::class);
        Actions\expectDone(GtmScriptTagRendererEvent::ACTION_AFTER_SCRIPT)
            ->once()
            ->with(DataLayer::class);

        $testee = new GtmScriptTagRenderer($dataLayer);

        ob_start();
        static::assertTrue($testee->render());
        $output = ob_get_clean();

        static::assertStringContainsString('<script>', $output);
        static::assertStringContainsString($expected_id, $output);
        static::assertStringContainsString($expected_name, $output);
        static::assertStringContainsString('</script>', $output);
    }

    /**
     * @test
     */
    public function testRenderInvalidId(): void
    {
        Actions\expectDone(LogEvent::ACTION)
            ->once();

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('id')
            ->once()
            ->andReturn('');

        $testee = new GtmScriptTagRenderer($dataLayer);
        static::assertFalse($testee->render());
    }

    /**
     * @test
     */
    public function testRenderCustomAttributes(): void
    {
        $expected_id = 'GTM-123456';
        $expected_name = 'foo';

        $expectedKey = 'key';
        $expectedValue = 'value';
        $customAttributes = [
            $expectedKey => $expectedValue,
        ];

        $expectedOutput = sprintf('<script %s>', $expectedKey . '="' . $expectedValue . '"');

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('id')
            ->once()
            ->andReturn($expected_id);
        $dataLayer->shouldReceive('name')
            ->once()
            ->andReturn($expected_name);

        Functions\when('esc_attr')
            ->returnArg(1);
        Functions\when('esc_js')
            ->returnArg(1);

        Filters\expectApplied(GtmScriptTagRendererEvent::FILTER_SCRIPT_ATTRIBUTES)
            ->once()
            ->andReturn($customAttributes);

        $testee = new GtmScriptTagRenderer($dataLayer);

        ob_start();
        static::assertTrue($testee->render());
        $output = ob_get_clean();

        static::assertStringContainsString($expectedOutput, $output);
    }
}
