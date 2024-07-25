<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Renderer;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollector;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\LogEvent;
use Inpsyde\GoogleTagManager\Renderer\NoscriptTagRenderer;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class NoscriptTagRendererTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $testee = NoscriptTagRenderer::new(Mockery::mock(DataLayer::class));
        static::assertInstanceOf(NoscriptTagRenderer::class, $testee);
    }

    /**
     * @test
     */
    public function testRender(): void
    {
        $expected_id = 'GTM-123456';
        $expected_data = ['foo' => 'bar'];

        $first_url = NoscriptTagRenderer::GTM_URL . '?id=' . $expected_id;
        $expected_url = $first_url . '&foo=bar';

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('gtmId')
            ->once()
            ->andReturn($expected_id);
        $dataLayer->shouldReceive('data')
            ->once()
            ->andReturn($expected_data);

        $testee = NoscriptTagRenderer::new($dataLayer);

        Functions\expect('add_query_arg')
            ->once()
            ->andReturn($expected_url);

        Functions\expect('esc_url')
            ->once()
            ->with($expected_url)
            ->andReturn($expected_url);

        ob_start();
        $testee->render();
        $output = ob_get_clean();

        static::assertStringContainsString('<noscript>', $output);
        static::assertStringContainsString('<iframe', $output);
        static::assertStringContainsString($expected_url, $output);
        static::assertStringContainsString('</iframe>', $output);
        static::assertStringContainsString('</noscript>', $output);
    }

    /**
     * @test
     */
    public function testRenderInvalidId(): void
    {
        Actions\expectDone(LogEvent::ACTION)
            ->once();

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('gtmId')
            ->once()
            ->andReturn('');

        $testee = NoscriptTagRenderer::new($dataLayer);

        ob_start();
        $testee->render();
        $output = ob_get_clean();

        static::assertSame('', $output);
    }

    /**
     * @test
     */
    public function testRenderAtBodyStartNoAutoInsert(): void
    {
        static::expectOutputString('');

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('autoInsertNoscript')
            ->once()
            ->andReturn(false);

        $testee = NoscriptTagRenderer::new($dataLayer);
        $testee->renderAtBodyStart([]);
    }

    /**
     * @test
     */
    public function testRenderAtBodyStartNoScript(): void
    {
        static::expectOutputString('');

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('autoInsertNoscript')
            ->once()
            ->andReturn(true);
        $dataLayer->shouldReceive('gtmId')
            ->once()
            ->andReturn('');

        $testee = NoscriptTagRenderer::new($dataLayer);
        $testee->renderAtBodyStart([]);
    }

    /**
     * @test
     */
    public function testRenderAtBodyStart(): void
    {
        $expected_id = 'GTM-123456';

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('autoInsertNoscript')
            ->once()
            ->andReturn(true);
        $dataLayer->shouldReceive('gtmId')
            ->once()
            ->andReturn($expected_id);
        $dataLayer->shouldReceive('data')
            ->once()
            ->andReturn([]);

        Functions\stubs(['add_query_arg' => '']);
        Functions\stubs(['esc_url' => '']);

        $testee = NoscriptTagRenderer::new($dataLayer);
        ob_start();
        $testee->renderAtBodyStart([]);
        $output = ob_get_clean();
        static::assertStringContainsString("<iframe ", $output);
    }
}
