<?php

namespace App\PublicModule\Components\UserLoginControl;

/**
 * Interface UserLoginControlFactory
 * @package App\PublicModule\Components\UserLoginControl
 * @author Jiří Andrlík
 */
interface UserLoginControlFactory
{
    public function create(): UserLoginControl;
}