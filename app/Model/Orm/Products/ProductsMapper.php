<?php

declare(strict_types=1);

namespace App\Model\Orm\Products;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class ProductsMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'products';
	}
}
