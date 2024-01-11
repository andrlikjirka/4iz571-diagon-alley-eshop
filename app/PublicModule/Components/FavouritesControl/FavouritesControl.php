<?php

declare(strict_types=1);

namespace App\PublicModule\Components\FavouritesControl;

use App\Model\Facades\ProductsFacade;
use App\Model\Orm\Products\Product;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Security\User;
use stdClass;

/**
 * Class FavouritesControl
 * @package App\PublicModule\Components\FavouritesControl
 * @author Martin Kovalski
 *
 * @property-read Template|stdClass $template
 */
class FavouritesControl extends Control
{
	public function __construct(
		private readonly User        $user,
		private readonly ProductsFacade $productsFacade
	) {}

	// favourites icon
	public function render(): void
	{
		if($this->user->isLoggedIn()) {
			$this->template->favouritesCount = $this->productsFacade->getFavouriteProductsCountByUser($this->user->getId());
		} else {
			$this->template->favouritesCount = 0;
		}
		$this->template->render(__DIR__ . '/templates/default.latte');
	}

	// favourites list on page
	public function renderList(): void
	{
		if($this->user->isLoggedIn()) {
			$this->template->favouriteProducts = $this->productsFacade->getFavouriteProductsByUser($this->user->getId());
		} else {
			$this->template->favouriteProducts = [];
		}
		$this->template->render(__DIR__ . '/templates/list.latte');
	}

	public function renderBtn(Product $product): void
	{
		$this->template->product = $product;
		$this->template->render(__DIR__ . '/templates/btn.latte');
	}


	/**
	 * @throws AbortException
	 */
	public function handleAddToFavourites(int $productId): void
	{
		$this->productsFacade->addProductToFavourites($this->user->getId(), $productId);

		$this->presenter->flashMessage('Produkt byl přidán do oblíbených', 'success');

		if ($this->presenter->isAjax()) {
			$this->presenter->redrawControl('flashes');
			$this->presenter->redrawControl('favourites');
			$this->presenter->redrawControl('products');
			$this->presenter->redrawControl('product');
		} else {
			$this->presenter->redirect('this');
		}
	}

	/**
	 * @throws AbortException
	 */
	public function handleRemoveFromFavourites(int $productId): void
	{
		$this->productsFacade->removeProductToFavourites($this->user->getId(), $productId);

		$this->presenter->flashMessage('Produkt byl odebrán z oblíbených', 'success');

		if ($this->presenter->isAjax()) {
			$this->presenter->redrawControl('flashes');
			$this->presenter->redrawControl('favourites');
			$this->presenter->redrawControl('products');
			$this->presenter->redrawControl('product');
		} else {
			$this->presenter->redirect('this');
		}
	}
}
