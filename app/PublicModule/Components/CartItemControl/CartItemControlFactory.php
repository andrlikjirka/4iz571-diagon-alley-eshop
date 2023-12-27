<?php

namespace App\PublicModule\Components\CartItemControl;

interface CartItemControlFactory
{
    public function create(): CartItemControl;
}