<?php

declare(strict_types=1);

namespace Pollen\Container;

use ArrayAccess;
use Closure;
use League\Container\Container as BaseContainer;
use League\Container\ReflectionContainer;

class Container extends BaseContainer implements ArrayAccess, ContainerInterface
{
    /**
     * @var array<string, <string>>
     */
    protected array $aliases = [];

    /**
     * @inheritDoc
     */
    public function enableAutoWiring(bool $cached = false): ContainerInterface
    {
        $reflectionContainer = new ReflectionContainer();

        if ($cached === true) {
            $reflectionContainer->cacheResolutions();
        }

        return $this->delegate($reflectionContainer);
    }

    /**
     * @inheritDoc
     */
    public function get($id, bool $new = false)
    {
        $id = $this->aliases[$id] ?? $id;

        return parent::get($id, $new);
    }

    /**
     * @inheritDoc
     */
    public function has($id) : bool
    {
        $id = $this->aliases[$id] ?? $id;

        return parent::has($id);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->add(
            $offset,
            $value instanceof Closure ? $value : function () use ($value) {
                return $value;
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        foreach ($this->definitions as $k => $definition) {
            if ($definition->getAlias() === $offset) {
                unset($this->definitions[$k]);
                break;
            }
        }
    }

    /**
     * Get service dynamically.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this[$key];
    }

    /**
     * Set service dynamically.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function __set(string $key, $value)
    {
        $this[$key] = $value;
    }

    /**
     * Check if dynamic service exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset(string $key)
    {
        return isset($this[$key]);
    }
}