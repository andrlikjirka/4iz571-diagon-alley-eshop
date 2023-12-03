<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Datagrids\ProductsDatagrid\ProductsDatagridControl;
use App\AdminModule\Datagrids\ProductsDatagrid\ProductsDatagridControlFactory;
use App\AdminModule\Forms\ProductsFormFactory;
use Nette;
use Nette\Application\UI\Form;
use Ublaboo\DataGrid\DataGrid;


/**
 * Class ProductsPresenter
 * @package App\AdminModule\Presenters
 * @author Martin Kovalski
 */
final class ProductsPresenter extends BasePresenter
{
	public function __construct(
		private readonly ProductsFormFactory $productsFormFactory,
		private readonly ProductsDatagridControlFactory $productsDatagridControlFactory
	) {
		parent::__construct();
	}

	public function actionEdit(?int $id)
	{

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

	protected function createComponentProductsDatagrid(): ProductsDatagridControl
	{
		return $this->productsDatagridControlFactory->create();
	}
}