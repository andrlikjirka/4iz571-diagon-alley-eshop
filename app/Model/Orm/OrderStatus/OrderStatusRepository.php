<?php

namespace App\Model\Orm\OrderStatus;

use Nextras\Orm\Repository\Repository;

class OrderStatusRepository extends Repository
{

    /**
     * @inheritDoc
     */
    public static function getEntityClassNames(): array
    {
        return [OrderStatus::class];
    }
}