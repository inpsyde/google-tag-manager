<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Exception;

use Psr\Container\ContainerExceptionInterface;

// phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong

/**
 * Class AlreadyBootedException
 *
 * @package Inpsyde\GoogleTagManager\Exception
 */
class AlreadyBootedException extends \InvalidArgumentException implements ContainerExceptionInterface
{

}
