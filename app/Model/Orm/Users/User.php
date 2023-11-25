<?php

declare(strict_types=1);

namespace App\Model\Orm\Users;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Addresses\Address;
use App\Model\Orm\Carts\Cart;
use App\Model\Orm\FavouriteProducts\FavouriteProduct;
use App\Model\Orm\ForgottenPasswords\ForgottenPassword;
use App\Model\Orm\Orders\Order;
use App\Model\Orm\Reviews\Review;
use App\Model\Orm\Roles\Role;


/**
 * @property int $id {primary}
 * @property string $name
 * @property string|NULL $email
 * @property string|NULL $facebookId
 * @property Role $role {1:1 Role, isMain=true, oneSided=true}
 * @property string|NULL $password
 * @property int $blocked {default 0}
 * @property int $deleted {default 0}
 * @property Address[] $addresses {1:m Address::$user} {virtual}
 * @property Cart|NULL $cart {1:1 Cart::$user, isMain=true} {virtual}
 * @property FavouriteProduct[] $favouriteProducts {1:m FavouriteProduct::$user} {virtual}
 * @property ForgottenPassword[] $forgottenPasswords {1:m ForgottenPassword::$user} {virtual}
 * @property Order[] $orders {1:m Order::$user} {virtual}
 * @property Review[] $reviews {1:m Review::$user} {virtual}
 */
class User extends Entity
{
}
