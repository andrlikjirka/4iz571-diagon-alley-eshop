<?php

declare(strict_types=1);

namespace App\Model\Facades;


use App\Model\Orm\Orm;
use App\Model\Orm\Products\Product;
use Exception;
use Tracy\Debugger;

/**
 * Class ProductsFacade
 * @package App\Model\Facades
 * @author Martin Kovalski
 */
class ProductsFacade
{
	public function __construct(
		private readonly Orm $orm
	) {}

	public function getProduct(int $id): Product
	{
		return $this->orm->products->getByIdChecked($id);
	}

	/**
	 * @throws Exception
	 */
	public function saveProduct(Product $product): void
	{
		try {
			$this->orm->products->persistAndFlush($product);
		} catch (Exception $e) {
			Debugger::log($e);
			$this->orm->products->getMapper()->rollback();
			throw new Exception('Produkt se nepodařilo uložit');
		}

	}
}