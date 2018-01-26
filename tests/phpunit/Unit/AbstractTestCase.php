<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;

/**
 * @package Inpsyde\GoogleTagManager\Tests\Unit
 */
abstract class AbstractTestCase extends TestCase
{

    /**
     * Sets up the environment.
     *
     * @return void
     */
    protected function setUp()
    {

        parent::setUp();
        Monkey\setUp();
    }

    /**
     * Tears down the environment.
     *
     * @return void
     */
    protected function tearDown()
    {

        Monkey\tearDown();
        parent::tearDown();
    }

}