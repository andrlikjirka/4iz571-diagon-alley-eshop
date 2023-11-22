<?php

declare(strict_types=1);

namespace App\Model\Orm\CartItems;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class CartItemsMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'cart_items';
	}
}
