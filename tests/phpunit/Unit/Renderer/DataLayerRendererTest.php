<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Renderer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollector;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Renderer\DataLayerRenderer;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class DataLayerRendererTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $testee = DataLayerRenderer::new(Mockery::mock(DataLayer::class));
        static::assertInstanceOf(DataLayerRenderer::class, $testee);
    }

    /**
     * @test
     */
    public function testRender(): void
    {
        $expected_data = ['foo' => 'bar'];
        $expected_name = 'baz';

        $dataLayer = Mockery::mock(DataLayer::class);
        $dataLayer->shouldReceive('dataLayerName')
            ->once()
            ->andReturn($expected_name);
        $dataLayer->shouldReceive('data')
            ->once()
            ->andReturn([$expected_data]);

        Functions\expect('esc_js')
            ->with(Mockery::type('string'))
            ->andReturnFirstArg();

        Functions\expect('wp_json_encode')
            ->with(Mockery::type('array'))
            ->andReturnUsing(
                function ($data) {
                    return json_encode($data);
                }
            );

        $testee = DataLayerRenderer::new($dataLayer);

        ob_start();
        static::assertTrue($testee->render());
        $output = ob_get_clean();

        static::assertStringContainsString('<script>', $output);
        static::assertStringContainsString('var ' . $expected_name, $output);
        static::assertStringContainsString($expected_name . '.push(', $output);
        static::assertStringContainsString(json_encode($expected_data), $output);
        static::assertStringContainsString('</script>', $output);
    }
}
