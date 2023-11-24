<?php

namespace App\PublicModule\Components\NavbarControl;

use Nette\Application\UI\Control;

class NavbarControl extends Control
{
    public function render($params = []): void
    {
        $this->template->render(__DIR__ . '/templates/default.latte');
    }
}
