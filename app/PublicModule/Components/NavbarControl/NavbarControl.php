<?php

namespace App\PublicModule\Components\NavbarControl;

use App\Model\Facades\CategoriesFacade;
use Nette\Application\UI\Control;

/**
 * Class NavbarControl
 * @package App\PublicModule\Components
 * @author Jiří Andrlík
 */
class NavbarControl extends Control
{

    /**
     * NavbarControl constructor
     * @param CategoriesFacade $categoriesFacade
     */
    public function __construct(
        public readonly CategoriesFacade $categoriesFacade
    )
    {}

    /**
     * Metoda renderující šablonu komponenty
     * @param $params
     * @return void
     */
    public function render($params = []): void
    {
        $this->template->categories = $this->categoriesFacade->findShowedCategoryTopParents(); //jen rodiče na první úrovni, všechny jejich děti vypisuji při průchodu rodičů v šabloně
        $this->template->render(__DIR__ . '/templates/default.latte');
    }
}
