<?php

declare(strict_types=1);

namespace App\Model\Orm\Categories;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Products\Product;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property int $id {primary}
 * @property string $name
 * @property ?Category $parent {m:1 Category::$children}
 * @property-read ICollection|Category[] $childrenShowed {virtual}
 * @property OneHasMany|Category[] $children {1:m Category::$parent}
 * @property int $showed {default 0}
 * @property Product[] $products {1:m Product::$category}
 */
class Category extends Entity
{
    public function getterChildrenShowed()
    {
        return $this->children->toCollection()->findBy(['showed' => true]);
    }
}
