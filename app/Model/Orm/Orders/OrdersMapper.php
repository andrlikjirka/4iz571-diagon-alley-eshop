<?php

declare(strict_types=1);

namespace App\Model\Orm\Orders;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class OrdersMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'orders';
	}
}
