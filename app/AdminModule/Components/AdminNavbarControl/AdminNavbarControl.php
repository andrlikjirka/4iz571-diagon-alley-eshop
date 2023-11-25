<?php

namespace App\AdminModule\Components\AdminNavbarControl;

use Nette\Application\UI\Control;

class AdminNavbarControl extends Control
{
    public function render($params = []): void
    {
        $this->template->render(__DIR__.'/templates/default.latte');
    }
}