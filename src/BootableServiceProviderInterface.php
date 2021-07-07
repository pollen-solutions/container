<?php

declare(strict_types=1);

namespace Pollen\Container;

interface BootableServiceProviderInterface extends ServiceProviderInterface
{
    /**
     * Booting.
     *
     * @return void
     */
    public function boot(): void;
}