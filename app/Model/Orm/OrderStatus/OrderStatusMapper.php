<?php

declare(strict_types=1);

namespace App\Model\Orm\OrderStatus;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class OrderStatusMapper extends DbalMapper
{
    public function getTableName(): string
    {
        return 'order_status';
    }
}