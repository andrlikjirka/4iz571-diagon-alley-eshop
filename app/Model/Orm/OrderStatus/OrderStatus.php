<?php

namespace App\Model\Orm\OrderStatus;

use App\Model\Orm\Orders\Order;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * @property int $id {primary}
 * @property string $name
 * @property OneHasMany|Order[] $orders {1:m Order::$orderStatus}
 */
class OrderStatus extends Entity
{

    public const RECEIVED = 1;
    public const IN_PROGRESS = 2;
    public const SETTLED = 3;
    public const CANCELLED = 4;

}