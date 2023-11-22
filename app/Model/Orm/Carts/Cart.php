<?php

declare(strict_types=1);

namespace App\Model\Orm\Carts;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\CartItems\CartItem;
use App\Model\Orm\Users\User;
use Nextras\Dbal\Utils\DateTimeImmutable;


/**
 * @property int $id {primary}
 * @property User $user {1:1 User::$cart}
 * @property DateTimeImmutable $lastModified {default now}
 * @property CartItem[] $cartItems {1:m CartItem::$cart}
 */
class Cart extends Entity
{
}
