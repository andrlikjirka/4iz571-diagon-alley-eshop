<?php

declare(strict_types=1);

namespace App\Model\Orm\Products;

use Nextras\Orm\Repository\Repository;

class ProductsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Product::class];
	}
}
