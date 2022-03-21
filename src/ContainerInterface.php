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
     * Add alternative alias service.
     *
     * @var string $definitionAlias
     * @var string $altAlias
     *
     * @return static
     */
    public function addAlias(string $definitionAlias, string $altAlias): ContainerInterface;

    /**
     * @return void
     */
    public function bootProviders(): void;

    /**
     * Enabling auto-wiring.
     *
     * @param bool $cached
     *
     * @return static
     */
    public function enableAutoWiring(bool $cached = false):  void;
}