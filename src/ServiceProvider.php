<?php

declare(strict_types=1);

namespace Pollen\Container;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Container\ContainerInterface as PsrContainer;

class ServiceProvider extends AbstractServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ContainerInterface
     */
    public function getContainer() : PsrContainer
    {
        return parent::getContainer();
    }

    /**
     * @inheritDoc
     */
    public function register(): void {}
}