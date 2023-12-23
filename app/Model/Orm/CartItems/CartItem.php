<?php

declare(strict_types=1);

namespace App\Model\Orm\CartItems;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Carts\Cart;
use App\Model\Orm\Products\Product;


/**
 * @property int $id {primary}
 * @property Cart $cart {m:1 Cart::$cartItems}
 * @property Product $product {m:1 Product, oneSided=true}
 * @property int $quantity {default 0}
 */
class CartItem extends Entity
{
}
