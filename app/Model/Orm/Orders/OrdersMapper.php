<?php

namespace App\Model\Orm\Orders;

use App\Model\Orm\AbstractMapper;

class OrdersMapper extends AbstractMapper
{
	public function getTableName(): string
	{
		return 'orders';
	}
}
