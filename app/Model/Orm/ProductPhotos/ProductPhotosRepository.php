<?php

declare(strict_types=1);

namespace App\Model\Orm\ProductPhotos;

use Nextras\Orm\Repository\Repository;

class ProductPhotosRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [ProductPhoto::class];
	}
}
