<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\App;

use Inpsyde\GoogleTagManager\Exception\ConfigAlreadyFrozenException;
use Inpsyde\GoogleTagManager\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
// phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType

/**
 * @package Inpsyde\GoogleTagManager\App
 */
class PluginConfig implements ContainerInterface
{

    /**
     * List of properties.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Record of deleted properties.
     *
     * @var array
     */
    protected $deleted = [];

    /**
     * Write and delete protection.
     *
     * @var bool
     */
    protected $frozen = false;

    /**
     * Set new value.
     *
     * @param  string $name
     * @param  mixed $value
     *
     * @throws ConfigAlreadyFrozenException
     *
     * @return PluginConfig
     */
    public function set(string $name, $value): PluginConfig
    {
        if ($this->frozen) {
            $this->stop(
                'This object has been frozen.
				You cannot set properties anymore.'
            );
        }

        $this->properties[$name] = $value;
        unset($this->deleted[$name]);

        return $this;
    }

    /**
     * Used for attempts to write to a frozen instance.
     *
     * Might be replaced by a child class.
     *
     * @param  string $msg Error message. Always be specific.
     * @param  string $code Re-use the same code to group error messages.
     *
     * @throws ConfigAlreadyFrozenException
     */
    protected function stop(string $msg, string $code = '')
    {
        if ('' === $code) {
            $code = __CLASS__;
        }
        throw new ConfigAlreadyFrozenException($msg, $code);
    }

    /**
     * Import an array or an object as properties.
     *
     * @param  array|object $var
     *
     * @throws ConfigAlreadyFrozenException
     *
     * @return PluginConfig
     */
    public function import($var): PluginConfig
    {
        if ($this->frozen) {
            $this->stop(
                'This object has been frozen.
				You cannot set properties anymore.'
            );
        }

        if (! is_array($var) && ! is_object($var)) {
            $this->stop(
                'Cannot import this variable.
				Use arrays and objects only, not a "'.gettype($var).'".'
            );
        }

        foreach ($var as $name => $value) {
            $this->properties[$name] = $value;
        }

        return $this;
    }

    /**
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function get($id)
    {
        if (! $this->has($id)) {
            throw new NotFoundException(sprintf('The given key "%s" was not found', $id));
        }

        return $this->properties[$id];
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id): bool
    {
        if (isset($this->properties[$id])) {
            return true;
        }

        if (isset($this->deleted[$id])) {
            return false;
        }

        return false;
    }

    /**
     * Get all properties.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->properties;
    }

    /**
     * Delete a key and set its name to the $deleted list.
     *
     * Further calls to has() and get() will not take this property into account.
     *
     * @param  string $name
     *
     * @throws ConfigAlreadyFrozenException
     *
     * @return PluginConfig
     */
    public function delete(string $name): PluginConfig
    {
        if ($this->frozen) {
            $this->stop(
                'This object has been frozen.
				You cannot delete properties anymore.'
            );
        }

        $this->deleted[$name] = true;
        unset($this->properties[$name]);

        return $this;
    }

    /**
     * Lock write access to this object's instance. Forever.
     *
     * @return PluginConfig
     */
    public function freeze(): PluginConfig
    {
        $this->frozen = true;

        return $this;
    }

    /**
     * Test from outside if an object has been frozen.
     *
     * @return boolean
     */
    public function isFrozen(): bool
    {
        return $this->frozen;
    }
}
