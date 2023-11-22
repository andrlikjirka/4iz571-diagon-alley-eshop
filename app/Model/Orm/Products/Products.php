<?php

namespace App\Model\Orm\Products;

use App\Model\Orm\AbstractEntity;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int $productId
 * @property string $name
 * @property string|NULL $description
 * @property DateTimeImmutable $created {default now}
 * @property DateTimeImmutable|NULL $updated
 * @property int $stock
 * @property int $categoryId
 */
class Products extends AbstractEntity
{
}
