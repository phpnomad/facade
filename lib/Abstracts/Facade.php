<?php

namespace Phoenix\Facade\Abstracts;

use Phoenix\Di\Container;
use Phoenix\Di\Exceptions\DiException;
use Phoenix\Di\Interfaces\CanSetContainer;
use Phoenix\Di\Traits\HasSettableContainer;
use Phoenix\Logger\Facades\Logger;
use Phoenix\Logger\Interfaces\LoggerStrategy;

/**
 * @template TAbstraction of object
 */
abstract class Facade implements CanSetContainer
{
    use HasSettableContainer;

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
            $result = $this->container->get($this->abstractInstance());
        } catch (DiException $e) {
            // Avoid using logger facade to prevent infinite loops.
            $this->container->get(LoggerStrategy::class)->critical(
                $e->getMessage(),
                ['container' => $this->container, 'abstract' => $this->abstractInstance()]
            );
            throw $e;
        }

        return $result;
    }
}