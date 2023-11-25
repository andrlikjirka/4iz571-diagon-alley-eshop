<?php

namespace App\Components\UserLoginControl;

/**
 * Interface UserLoginControlFactory
 * @package App\PublicModule\Components\UserLoginControl
 * @author Jiří Andrlík
 */
interface UserLoginControlFactory
{
    public function create(): UserLoginControl;
}