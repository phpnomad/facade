<?php

namespace Phoenix\Facade\Interfaces;

use Phoenix\Facade\Abstracts\Facade;

interface HasFacades
{
    /**
     * @return Facade[]
     */
    public function getFacades(): array;
}