<?php

declare(strict_types=1);

namespace Pollen\Container;

class BootableServiceProvider extends ServiceProvider implements BootableServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function boot(): void {}
}