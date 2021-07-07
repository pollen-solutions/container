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
     * Activation de l'auto wring
     *
     * @param bool $cached
     *
     * @return static
     */
    public function enableAutoWiring(bool $cached = false): ContainerInterface;
}