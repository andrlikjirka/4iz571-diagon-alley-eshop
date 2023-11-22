<?php

declare(strict_types=1);

namespace App\Model\Orm\CartItems;

use Nextras\Orm\Repository\Repository;

class CartItemsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [CartItem::class];
	}
}
