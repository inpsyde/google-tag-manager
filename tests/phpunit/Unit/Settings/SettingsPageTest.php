<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Settings;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use ChriCo\Fields\Element\ElementInterface;
use Inpsyde\GoogleTagManager\Event\LogEvent;
use Inpsyde\GoogleTagManager\Http\Request;
use Inpsyde\GoogleTagManager\Settings\Auth\SettingsPageAuthInterface;
use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\View\SettingsPageViewInterface;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class SettingsPageTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $view = Mockery::mock(SettingsPageViewInterface::class);
        $view->shouldReceive('name')
            ->once()
            ->andReturn();

        $repo = Mockery::mock(SettingsRepository::class);
        $auth = Mockery::mock(SettingsPageAuthInterface::class);

        $testee = new SettingsPage($view, $repo, $auth);
        static::assertInstanceOf(SettingsPage::class, $testee);
    }

    /**
     * @test
     */
    public function testRegister(): void
    {
        $expected_hook = 'page-hook';

        Functions\expect('add_options_page')
            ->once()
            ->with(
                Mockery::type('string'),
                Mockery::type('string'),
                Mockery::type('string'),
                Mockery::type('string'),
                Mockery::type('callable')
            )
            ->andReturn($expected_hook);

        $view = Mockery::mock(SettingsPageViewInterface::class);
        $view->shouldReceive('name')
            ->times(3)
            ->andReturn('foo');
        $view->shouldReceive('slug')
            ->once()
            ->andReturn('baz');

        $auth = Mockery::mock(SettingsPageAuthInterface::class);
        $auth->shouldReceive('cap')
            ->once()
            ->andReturn('bar');

        $repo = Mockery::mock(SettingsRepository::class);
        $repo->shouldReceive('options')
            ->once()
            ->andReturn([]);

        Actions\expectAdded(
            'load-' . $expected_hook,
            '\Inpsyde\GoogleTagManager\Settings\SettingsPage->update()'
        );

        $testee = new SettingsPage($view, $repo, $auth);

        static::assertTrue($testee->register());
    }

    /**
     * @test
     */
    public function testUpdateWrongRequestMethod(): void
    {
        $view = Mockery::mock(SettingsPageViewInterface::class);
        $view->shouldReceive('name')
            ->andReturn();

        $repo = Mockery::mock(SettingsRepository::class);
        $auth = Mockery::mock(SettingsPageAuthInterface::class);
        $request = new Request([], [], [], ['REQUEST_METHOD' => 'GET']);

        static::assertFalse((new SettingsPage($view, $repo, $auth, $request))->update());
    }

    /**
     * @test
     */
    public function testUpdateUpdateFails(): void
    {
        \Brain\Monkey\Actions\expectDone(LogEvent::ACTION);

        $view = Mockery::mock(SettingsPageViewInterface::class);
        $view->shouldReceive('name')
            ->andReturn();

        $repo = Mockery::mock(SettingsRepository::class);
        $repo->shouldReceive('options')
            ->andReturn([]);
        $repo->shouldReceive('update')
            ->with(Mockery::type('array'))
            ->andReturn(false);

        $auth = Mockery::mock(SettingsPageAuthInterface::class);
        $auth->shouldReceive('isAllowed')
            ->with(Mockery::type('array'))
            ->andReturn(true);

        $request = new Request([], [], [], ['REQUEST_METHOD' => 'POST']);

        static::assertFalse((new SettingsPage($view, $repo, $auth, $request))->update());
    }

    /**
     * @test
     */
    public function testAddElement(): void
    {
        $view = Mockery::mock(SettingsPageViewInterface::class);
        $view->shouldReceive('name')
            ->once()
            ->andReturn();

        $repo = Mockery::mock(SettingsRepository::class);

        $auth = Mockery::mock(SettingsPageAuthInterface::class);

        $element = Mockery::mock(ElementInterface::class);
        $element->shouldReceive('name')
            ->andReturn('');

        $testee = new SettingsPage($view, $repo, $auth);
        static::assertNull($testee->addElement($element));
    }
}