<?php

namespace App\Model\Orm\Products;

use App\Model\Orm\AbstractRepository;

class ProductsRepository extends AbstractRepository
{
	public static function getEntityClassNames(): array
	{
		return [Products::class];
	}
}
