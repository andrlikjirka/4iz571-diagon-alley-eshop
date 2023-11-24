<?php

namespace App\PublicModule\Components\UserLoginControl;

interface UserLoginControlFactory
{
    public function create(): UserLoginControl;
}