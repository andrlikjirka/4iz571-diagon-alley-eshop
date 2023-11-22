<?php

namespace App\Model\Orm\Orders;

use App\Model\Orm\AbstractRepository;

class OrdersRepository extends AbstractRepository
{
	public static function getEntityClassNames(): array
	{
		return [Orders::class];
	}
}
