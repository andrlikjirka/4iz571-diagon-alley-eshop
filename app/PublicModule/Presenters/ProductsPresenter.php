<?php

namespace App\PublicModule\Presenters;

use App\Model\Facades\CategoriesFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Orm\Categories\Category;
use App\PublicModule\Components\ProductCardControl\ProductCardControl;
use App\PublicModule\Components\ProductCardControl\ProductCardControlFactory;

class ProductsPresenter extends BasePresenter
{
    /** @persistent */
    public ?int $categoryId = null;

    public function __construct(
        private readonly ProductCardControlFactory $productControlFactory,
        private readonly ProductsFacade            $productsFacade,
        private readonly CategoriesFacade $categoriesFacade
    )
    {
        parent::__construct();
    }

    public function renderDefault(): void
    {
        if (!empty($this->categoryId)) {
            //pokud se získat danou kategorii
            try {
                $category = $this->categoriesFacade->getCategoryById($this->categoryId);
            } catch (\Exception $e) {
                $this->flashMessage('Kategorie nenalezena.', 'warning');
                $this->redirect('this', ['categoryId' => null]);
            }
            $this->template->currentCategory = $category;
            $products = $this->productsFacade->getShowedProductsByCategory($category);
        } else {
            $products = $this->productsFacade->getAllShowedProducts();
        }

        //TODO: paginator

        $this->template->products = $products;
    }

    public function createComponentProductCard(): ProductCardControl
    {
        return $this->productControlFactory->create();
    }

}