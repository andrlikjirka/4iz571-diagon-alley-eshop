<?php

namespace App\PublicModule\Components\ProductCardControl;

/**
 * Interface ProductCardControlFactory
 * @package App\PublicModule\Components
 * @author Jiří Andrlík
 */
interface ProductCardControlFactory
{
    public function create(): ProductCardControl;
}