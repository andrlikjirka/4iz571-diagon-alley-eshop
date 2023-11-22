<?php

declare(strict_types=1);

namespace App\Model\Orm\Reviews;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Products\Product;
use App\Model\Orm\Users\User;


/**
 * @property int $id {primary}
 * @property string|NULL $text
 * @property int $stars
 * @property ?User $user {m:1 User::$reviews}
 * @property Product $product {m:1 Product::$reviews}
 */
class Review extends Entity
{
}
