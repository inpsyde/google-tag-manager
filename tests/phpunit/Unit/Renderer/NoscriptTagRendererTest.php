<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Renderer;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\LogEvent;
use Inpsyde\GoogleTagManager\Renderer\NoscriptTagRenderer;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class NoscriptTagRendererTest extends AbstractTestCase
{
    public function test_basic()
    {
        $testee = new NoscriptTagRenderer(Mockery::mock(DataLayer::class));
        static::assertInstanceOf(NoscriptTagRenderer::class, $testee);
    }

    public function test_render()
    {
        $expected_id = 'GTM-123456';
        $expected_data = ['foo' => 'bar'];

        $first_url = NoscriptTagRenderer::GTM_URL . '?id=' . $expected_id;
        $expected_url = $first_url . '&foo=bar';

        $data = Mockery::mock(DataCollectorInterface::class);
        $data->shouldReceive('data')
            ->once()
            ->andReturn($expected_data);

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('id')
            ->once()
            ->andReturn($expected_id);
        $dataLayer->shouldReceive('data')
            ->once()
            ->andReturn([$data]);

        $testee = new NoscriptTagRenderer($dataLayer);

        Functions\expect('add_query_arg')
            ->once()
            ->with(['id' => $expected_id], NoscriptTagRenderer::GTM_URL)
            ->andReturn($first_url)
            ->andAlsoExpectIt('add_query_arg')
            ->once()
            ->with($expected_data, $first_url)
            ->andReturn($expected_url);

        ob_start();
        $testee->render();
        $output = ob_get_clean();

        static::assertContains('<noscript>', $output);
        static::assertContains('<iframe', $output);
        static::assertContains($expected_url, $output);
        static::assertContains('</iframe>', $output);
        static::assertContains('</noscript>', $output);
    }

    public function test_render__invalid_id()
    {
        Actions\expectDone(LogEvent::ACTION)
            ->once();

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('id')
            ->once()
            ->andReturn('');

        $testee = new NoscriptTagRenderer($dataLayer);

        ob_start();
        $testee->render();
        $output = ob_get_clean();

        static::assertSame('', $output);
    }

    public function test_render_at_body_start__no_auto_insert()
    {
        static::expectOutputString('');

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('autoInsertNoscript')
            ->once()
            ->andReturn(false);

        $testee = new NoscriptTagRenderer($dataLayer);
        $testee->renderAtBodyStart([]);
    }

    public function test_render_at_body_start__no_noscript()
    {
        static::expectOutputString('');

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('autoInsertNoscript')
            ->once()
            ->andReturn(true);
        $dataLayer->shouldReceive('id')
            ->once()
            ->andReturn('');

        $testee = new NoscriptTagRenderer($dataLayer);
        $testee->renderAtBodyStart([]);
    }

    public function test_render_at_body_start()
    {
        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('autoInsertNoscript')
            ->once()
            ->andReturn(true);
        $dataLayer->shouldReceive('id')
            ->once()
            ->andReturn('');
        $dataLayer->shouldReceive('data')
            ->once()
            ->andReturn([]);

        Functions\stubs(['add_query_arg' => '']);

        $testee = new NoscriptTagRenderer($dataLayer);
        ob_start();
        $testee->renderAtBodyStart([]);
        $output = ob_get_clean();
        static::assertContains("<iframe ", $output);
    }
}
