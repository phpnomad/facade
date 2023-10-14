<?php

namespace Phoenix\Facade\Abstracts;

use Phoenix\Di\Container;
use Phoenix\Di\Exceptions\DiException;
use Phoenix\Di\Interfaces\CanSetContainer;

/**
 * @template TAbstraction of object
 */
abstract class Facade implements CanSetContainer
{
    protected Container $container;

    /**
     * @param Container $container
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return class-string<TAbstraction>
     */
    abstract protected function abstractInstance(): string;

    /**
     * @return TAbstraction
     */
    protected function getContainedInstance()
    {
        try {
            return $this->container->get($this->abstractInstance());
        } catch (DiException $e) {
            //TODO: LOG THIS EXCEPTION.
        }
    }
}