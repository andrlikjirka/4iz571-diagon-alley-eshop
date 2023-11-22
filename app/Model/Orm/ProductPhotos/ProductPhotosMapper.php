<?php

declare(strict_types=1);

namespace App\Model\Orm\ProductPhotos;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class ProductPhotosMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'product_photos';
	}
}
