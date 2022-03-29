<?php

declare(strict_types=1);

namespace Pollen\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface as PsrContainer;
use Psr\Container\NotFoundExceptionInterface;

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
     * Finds a new entry of the container by its identifier and returns it.
     *
     * @param string $id
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     *
     * @return mixed
     */
    public function getNew(string $id);

    /**
     * Enabling auto-wiring.
     *
     * @param bool $cached
     *
     * @return void
     */
    public function enableAutoWiring(bool $cached = false):  void;
}