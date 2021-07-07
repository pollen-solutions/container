<?php

declare(strict_types=1);

namespace Pollen\Container;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @mixin \League\Container\Container
 */
interface ContainerInterface extends PsrContainer
{
    /**
     * Enabling auto-wiring.
     *
     * @param bool $cached
     *
     * @return static
     */
    public function enableAutoWiring(bool $cached = false): ContainerInterface;
}