<?php

namespace App\PublicModule\Components\ProductCardControl;

use App\Model\Facades\ProductsFacade;
use App\Model\Orm\Products\Product;
use Nette\Application\UI\Control;

/**
 * Class ProductCardControl
 * @package App\PublicModule\Components
 * @author Jiří Andrlík
 */
class ProductCardControl extends Control
{

    /**
     * Metoda vykreslující šablonu komponenty
     * @param Product $product
     * @return void
     */
    public function render(Product $product): void
    {
        $this->template->product = $product;
        $this->template->render(__DIR__ . '/templates/default.latte');
    }

}