<?php

declare(strict_types=1);

namespace App\Model\Orm\OrderItems;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class OrderItemsMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'order_items';
	}
}
