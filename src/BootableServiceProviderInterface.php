<?php

declare(strict_types=1);

namespace Pollen\Container;

interface BootableServiceProviderInterface extends ServiceProviderInterface
{
    /**
     * Initialisation du fournisseur de service.
     *
     * @return void
     */
    public function boot(): void;
}