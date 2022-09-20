<?php

declare(strict_types=1);

namespace Pollen\Container;

use League\Container\Definition\DefinitionAggregate as BaseDefinitionAggregate;
use League\Container\Definition\DefinitionInterface;

class DefinitionAggregate extends BaseDefinitionAggregate
{
    /**
     * {@inheritdoc}
     */
    public function add(string $id, $definition, bool $shared = false): DefinitionInterface
    {
        if (!$definition instanceof DefinitionInterface) {
            $definition = new Definition($id, $definition);
        }

        $this->definitions[] = $definition
            ->setAlias($id)
            ->setShared($shared);

        return $definition;
    }
}
