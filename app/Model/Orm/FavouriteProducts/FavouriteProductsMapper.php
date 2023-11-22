<?php

declare(strict_types=1);

namespace App\Model\Orm\FavouriteProducts;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class FavouriteProductsMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'favourite_products';
	}
}
