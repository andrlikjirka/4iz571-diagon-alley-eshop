<?php

declare(strict_types=1);

namespace App\Model\Orm\Carts;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\CartItems\CartItem;
use App\Model\Orm\Users\User;
use Nextras\Dbal\Utils\DateTimeImmutable;


/**
 * @property int $id {primary}
 * @property ?User $user {1:1 User::$cart, isMain=true}
 * @property DateTimeImmutable $lastModified {default now}
 * @property CartItem[] $cartItems {1:m CartItem::$cart}
 * @property-read int $totalCount {virtual}
 * @property-read int $totalPrice {virtual}
 */
class Cart extends Entity
{
    public function getterTotalCount(): int
    {
        $result = 0;
        foreach ($this->cartItems as $cartItem) {
            $result += $cartItem->quantity;
        }
        return $result;
    }

    public function getterTotalPrice(): array
    {
        $totalPrice = ['galleon' => 0, 'sickle' => 0, 'knut' => 0];
        foreach ($this->cartItems as $cartItem) {
            $totalPrice['galleon'] += $cartItem->quantity * $cartItem->product->galleonPrice;
            $totalPrice['sickle'] += $cartItem->quantity * $cartItem->product->sicklePrice;
            $totalPrice['knut'] += $cartItem->quantity * $cartItem->product->knutPrice;
        }

        $totalPrice['sickle'] += floor($totalPrice['knut'] / 29);
        $totalPrice['knut'] = $totalPrice['knut'] % 29;

        $totalPrice['galleon'] += floor($totalPrice['sickle'] / 17);
        $totalPrice['sickle'] = $totalPrice['sickle'] % 17;

        return $totalPrice;
    }


}
