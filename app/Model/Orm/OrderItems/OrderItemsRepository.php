<?php

declare(strict_types=1);

namespace App\Model\Orm\OrderItems;

use Nextras\Orm\Repository\Repository;

class OrderItemsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [OrderItem::class];
	}
}
