<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\DataGrids\ProductsDataGrid\ProductsDataGridControl;
use App\AdminModule\DataGrids\ProductsDataGrid\ProductsDataGridControlFactory;
use App\AdminModule\Forms\ProductsFormFactory;
use App\Model\Facades\ProductsFacade;
use Nette\Application\UI\Form;


/**
 * Class ProductsPresenter
 * @package App\AdminModule\Presenters
 * @author Martin Kovalski
 */
final class ProductsPresenter extends BasePresenter
{
	public function __construct(
		private readonly ProductsFormFactory $productsFormFactory,
		private readonly ProductsDataGridControlFactory $productsDataGridControlFactory,
		private readonly ProductsFacade $productsFacade
	) {
		parent::__construct();
	}

	public function actionEdit(?int $id): void
	{
		$product = $this->productsFacade->getProduct($id);

		$defaultValues = [
			'productId' => $product->id,
			'name' => $product->name,
			'description' => $product->description,
			'stock' => $product->stock,
			'category' => $product->category->id,
			'showed' => $product->showed,
			'galleonPrice' => $product->galleonPrice,
			'sicklePrice' => $product->sicklePrice,
			'knutPrice' => $product->knutPrice
		];
		$this->getComponent('productsForm')->setDefaults($defaultValues);
	}

	public function renderEdit(?int $id): void
	{
		$this->template->productId = $id;
	}

	protected function createComponentProductsForm(): Form
	{
		$onSuccess = function (string $message) {
			$this->flashMessage($message, 'success');
			$this->redirect('Products:default');
		};
		$onFailed = function (string $message) {
			$this->flashMessage($message, 'danger');
			$this->redirect('this');
		};

		return $this->productsFormFactory->create($onSuccess, $onFailed);
	}

	protected function createComponentProductsDataGrid(): ProductsDatagridControl
	{
		return $this->productsDataGridControlFactory->create();
	}
}