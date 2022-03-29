<?php

declare(strict_types=1);

namespace Pollen\Container;

use ArrayAccess;
use Closure;
use League\Container\Container as BaseContainer;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container extends BaseContainer implements ArrayAccess, ContainerInterface
{
    /**
     * @var array<string, <string>>
     */
    protected array $aliases = [];

    protected bool $providersBooted = false;

    /**
     * @inheritDoc
     */
    public function addAlias(string $definitionAlias, string $altAlias): ContainerInterface
    {
        $this->aliases[$altAlias] = $definitionAlias;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bootProviders(): void
    {
        if (!$this->providersBooted) {
            foreach ($this->providers as $provider) {
                if ($provider instanceof BootableServiceProviderInterface) {
                    $provider->boot();
                }
            }
            $this->providersBooted = true;
        }
    }
    
    /**
     * @inheritDoc
     */
    public function enableAutoWiring(bool $cached = false): void
    {
        $reflectionContainer = new ReflectionContainer();

        if ($cached === true) {
            $reflectionContainer->cacheResolutions();
        }

        $this->delegate($reflectionContainer);
    }

    /**
     * @inheritDoc
     */
    public function get($id, bool $new = false)
    {
        return parent::get($this->aliases[$id] ?? $id);
    }

    /**
     * @inheritDoc
     */
    public function getNew(string $id)
    {
        return parent::get($this->aliases[$id] ?? $id, true);
    }

    /**
     * @inheritDoc
     */
    public function has($id) : bool
    {
        return parent::has($this->aliases[$id] ?? $id);
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
        try {
            return $this->get($offset);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->add(
            $offset,
            $value instanceof Closure ? $value : static function () use ($value) {
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