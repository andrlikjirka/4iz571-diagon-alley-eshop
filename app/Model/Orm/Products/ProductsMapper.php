<?php

namespace App\Model\Orm\Products;

use App\Model\Orm\AbstractMapper;

class ProductsMapper extends AbstractMapper
{
	public function getTableName(): string
	{
		return 'products';
	}
}
