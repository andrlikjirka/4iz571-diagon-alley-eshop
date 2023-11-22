<?php

declare(strict_types=1);

namespace App\Model\Orm\ProductPhotos;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Products\Product;


/**
 * @property int $id {primary}
 * @property string $path
 * @property Product $product {m:1 Product::$productPhotos}
 */
class ProductPhoto extends Entity
{
}
