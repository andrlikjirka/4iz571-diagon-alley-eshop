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
 * @property-read Category[] $childrenRecursively {virtual}
 * @property-read Category[] $childrenShowedRecursively {virtual}
 * @property OneHasMany|Category[] $children {1:m Category::$parent}
 * @property int $showed {default 0}
 * @property bool $deleted {default 0}
 * @property-read OneHasMany|Product[] $products {virtual}
 * @property OneHasMany|Product[] $allProducts {1:m Product::$category}
 */
class Category extends Entity
{
    public function getterChildrenShowed()
    {
        return $this->children->toCollection()->findBy(['showed' => true]);
    }

    public function getterProducts()
    {
        return $this->allProducts->toCollection()->findBy(['deleted' => false]);
    }

    public function getterChildrenRecursively(): array
    {
        $childrenRecursively = array();
        $stack = $this->children->toCollection()->fetchAll();
        while (!empty($stack)) {
            $currentCategory = array_pop($stack);
            $childrenRecursively[] = $currentCategory;
            foreach ($currentCategory->children as $child) {
                $stack[] = $child;
            }
        }
        return $childrenRecursively;
    }

    public function getterChildrenShowedRecursively(): array
    {
        $childrenRecursively = array();
        $stack = $this->childrenShowed->fetchAll();
        while (!empty($stack)) {
            $currentCategory = array_pop($stack);
            $childrenRecursively[] = $currentCategory;
            foreach ($currentCategory->childrenShowed as $child) {
                $stack[] = $child;
            }
        }
        return $childrenRecursively;
    }

}
