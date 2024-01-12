<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\DataGrids\ProductsDataGrid\ProductsDataGridControl;
use App\AdminModule\DataGrids\ProductsDataGrid\ProductsDataGridControlFactory;
use App\AdminModule\Forms\ProductsFormFactory;
use App\Model\Facades\ProductsFacade;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Zet\FileUpload\Model\DefaultFile;


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
		if($id) {
			//product default values
			$product = $this->productsFacade->getProduct($id);

			$defaultValues = [
				'productId' => $product->id,
				'name' => $product->name,
				'summary' => $product->summary,
				'description' => $product->description,
				'stock' => $product->stock,
				'category' => $product->category?->id,
				'showed' => $product->showed,
				'galleonPrice' => $product->galleonPrice,
				'sicklePrice' => $product->sicklePrice,
				'knutPrice' => $product->knutPrice
			];
			$this->getComponent('productsForm')->setDefaults($defaultValues);

			//product photos default values
			$files = [];
			foreach ($product->productPhotos as $productPhoto) {
				$file = new DefaultFile();
				$file->setPreview('/uploads/products/' . $productPhoto->name);
				$file->setFileName($productPhoto->name);
				$file->setIdentifier($productPhoto->name);
				$file->onDelete[] = function (string $fileName) use ($productPhoto) {
					$this->productsFacade->deleteProductPhoto($productPhoto);
				};

				$files[] = $file;
			}
			$this->getComponent('productsForm')->getComponent('Fotografie')->setDefaultFiles($files);
		}
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

	/**
	 * @throws AbortException
	 */
	public function actionReviews(int $productId): void
	{
		if(!$this->productsFacade->getProduct($productId)) {
			$this->flashMessage('Produkt nebyl nalezen', 'danger');
			$this->redirect('Products:default');
		}
	}

	public function renderReviews(int $productId): void
	{
		$this->template->product = $this->productsFacade->getProduct($productId);
	}

	/**
	 * @throws AbortException
	 */
	public function handleDeleteReview(int $reviewId): void
	{
		try {
			$this->productsFacade->deleteReview($reviewId);
			$this->flashMessage('Recenze byla úspěšně smazána.', 'success');
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage(), 'danger');
		}
		$this->redirect('this');
	}
}