<?php

namespace App\PublicModule\Components\NavbarControl;

interface NavbarControlFactory
{
    public function create(): NavbarControl;
}