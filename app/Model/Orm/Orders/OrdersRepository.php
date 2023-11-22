<?php

declare(strict_types=1);

namespace App\Model\Orm\Orders;

use Nextras\Orm\Repository\Repository;

class OrdersRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Order::class];
	}
}
