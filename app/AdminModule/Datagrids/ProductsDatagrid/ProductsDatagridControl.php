<?php

declare(strict_types=1);

namespace App\AdminModule\Datagrids\ProductsDatagrid;


use App\Model\Facades\ProductsFacade;
use App\Model\Orm\Products\Product;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use stdClass;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridException;

/**
 * Class ProductsDatagrid
 * @package App\AdminModule\Datagrids
 * @author Martin Kovalski
 *
 * @property-read Template|stdClass $template
 */
class ProductsDatagridControl extends Control
{
	public function __construct(
		private readonly ProductsFacade $productsFacade
	) {}

	/**
	 * @throws DataGridException
	 */
	public function createComponentDatagrid(): DataGrid
	{
		$grid = new DataGrid();

		$grid->setDataSource($this->productsFacade->findAllProducts());

		$grid->addColumnNumber('id', 'ID produktu')
			->setSortable();

		$grid->addColumnText('name', 'Název produktu')
			->setSortable()
			->setFilterText();

		$grid->addColumnNumber('price', 'Cena produktu')
			->setRenderer(function (Product $product): string {
				return $product->galleonPrice . "G " . $product->sicklePrice . "S " . $product->knutPrice . "K";
			});

		$grid->addActionCallback('edit', 'Upravit produkt')
			->setClass('btn btn-sm btn-default btn-secondary ajax')
			->onClick[] = function ($itemId): void {
				$this->presenter->redirect('Products:edit', ['id' => $itemId]);
		};

		$grid->setItemsPerPageList([1, 10, 20, 50, 100, 200, 500]);

		return $grid;
	}

	public function render(): void
	{
		$this->template->render(__DIR__ . '/productsDatagrid.latte');
	}
}