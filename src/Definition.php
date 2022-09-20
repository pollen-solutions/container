<?php

declare(strict_types=1);

namespace Pollen\Container;

use InvalidArgumentException;
use League\Container\Definition\Definition as BaseDefinition;
use ReflectionClass;
use ReflectionException;
use Throwable;

class Definition extends BaseDefinition
{
    /**
     * Resolve a class.
     *
     * @param string $concrete
     *
     * @return object
     *
     * @throws ReflectionException
     */
    protected function resolveClass(string $concrete): object
    {
        $resolved = $this->resolveArguments($this->arguments);

        if (!$resolved) {
            try {
                return $this->reflectionResolvedArgumentsInstance($concrete);
            } catch (Throwable $e) {
                unset($e);
            }
        }

        return (new ReflectionClass($concrete))->newInstanceArgs($resolved);
    }

    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \ReflectionException
     */
    protected function reflectionResolvedArgumentsInstance(string $class)
    {
        $reflectionClass = new ReflectionClass($class);

        if (($constructor = $reflectionClass->getConstructor()) === null) {
            throw new InvalidArgumentException();
        }

        if (($params = $constructor->getParameters()) === []) {
            throw new InvalidArgumentException();
        }

        $newInstanceParams = [];
        foreach ($params as $param) {
            if ($this->getContainer()->has($param->getName())) {
                $newInstanceParams[] = $this->getContainer()->get($param->getName());
            } else {
                $newInstanceParams[] = $param->getClass() === null
                    ? $param->getDefaultValue()
                    : $this->reflectionResolvedArgumentsInstance($param->getClass()->getName());
                }
        }

        return $reflectionClass->newInstanceArgs(
            $newInstanceParams
        );
    }
}
