<?php

namespace App\AdminModule\Components\AdminNavbarControl;

interface AdminNavbarControlFactory
{
    public function create(): AdminNavbarControl;
}