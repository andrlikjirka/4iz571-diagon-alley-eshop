<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use App\Model\Facades\CategoriesFacade;
use App\Model\Facades\ProductsFacade;


/**
 * Class HomepagePresenter
 * @package App\PublicModule\Presenters
 * @author Martin Kovalski
 */
final class HomepagePresenter extends BasePresenter
{
	public function __construct(
		private readonly ProductsFacade $productsFacade,
		private readonly CategoriesFacade $categoriesFacade
	) {
		parent::__construct();
	}

	public function renderSitemap(): void
	{
		$this->template->categories = $this->categoriesFacade->getAllCategories();
		$this->template->products = $this->productsFacade->getAllProducts();
	}
}
