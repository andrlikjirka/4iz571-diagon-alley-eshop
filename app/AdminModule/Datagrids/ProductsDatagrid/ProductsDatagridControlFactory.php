<?php

declare(strict_types=1);

namespace App\AdminModule\Datagrids\ProductsDatagrid;


/**
 * Interface ProductsDatagridControlFactory
 * @package App\AdminModule\Datagrids\ProductsDatagrid
 * @author Martin Kovalski
 */
interface ProductsDatagridControlFactory
{
	public function create(): ProductsDatagridControl;
}
