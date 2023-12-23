<?php

namespace App\PublicModule\Components\CartControl;

interface CartControlFactory
{
    public function create(): CartControl;
}