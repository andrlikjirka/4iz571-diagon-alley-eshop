<?php

declare(strict_types=1);

namespace App\Model\Orm\Products;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Categories\Category;
use App\Model\Orm\ProductPhotos\ProductPhoto;
use App\Model\Orm\Reviews\Review;
use Nextras\Dbal\Utils\DateTimeImmutable;


/**
 * @property int $id {primary}
 * @property string $name
 * @property string|NULL $description
 * @property DateTimeImmutable $created {default now}
 * @property DateTimeImmutable|NULL $updated
 * @property int $stock
 * @property Category $category {m:1 Category::$products}
 * @property int $showed
 * @property int $deleted
 * @property ProductPhoto[] $productPhotos {1:m ProductPhoto::$product}
 * @property Review[] $reviews {1:m Review::$product}
 */
class Product extends Entity
{
}