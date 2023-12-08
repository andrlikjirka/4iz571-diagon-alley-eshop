<?php

declare(strict_types=1);

namespace App\Model\Orm\Categories;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Products\Product;


/**
 * @property int $id {primary}
 * @property string $name
 * @property ?Category $parent {m:1 Category::$children}
 * @property Category[] $children {1:m Category::$parent}
 * @property int $showed {default 0}
 * @property Product[] $products {1:m Product::$category}
 */
class Category extends Entity
{
}
