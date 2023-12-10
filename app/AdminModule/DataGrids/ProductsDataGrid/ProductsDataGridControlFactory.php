<?php

declare(strict_types=1);

namespace App\AdminModule\DataGrids\ProductsDatagrid;


/**
 * Interface ProductsDataGridControlFactory
 * @package App\AdminModule\DataGrids\ProductsDataGrid
 * @author Martin Kovalski
 */
interface ProductsDataGridControlFactory
{
	public function create(): ProductsDatagridControl;
}
