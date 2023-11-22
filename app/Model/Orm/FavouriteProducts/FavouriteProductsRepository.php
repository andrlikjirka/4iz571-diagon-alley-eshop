<?php

declare(strict_types=1);

namespace App\Model\Orm\FavouriteProducts;

use Nextras\Orm\Repository\Repository;

class FavouriteProductsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [FavouriteProduct::class];
	}
}
