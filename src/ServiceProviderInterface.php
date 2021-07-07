<?php

declare(strict_types=1);

namespace Pollen\Container;

use League\Container\ServiceProvider\ServiceProviderInterface as BaseBaseServiceProviderInterface;

interface ServiceProviderInterface extends BaseBaseServiceProviderInterface
{
    /**
     * Déclaration de services.
     *
     * @return void
     */
    public function register(): void;
}