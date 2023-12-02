<?php

namespace App\PublicModule\Components\NavbarControl;

/**
 * Interface NavbarControlFactory
 * @package App\PublicModule\Components
 * @author Jiří Andrlík
 */
interface NavbarControlFactory
{
    public function create(): NavbarControl;
}