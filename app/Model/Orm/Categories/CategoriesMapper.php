<?php

declare(strict_types=1);

namespace App\Model\Orm\Categories;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class CategoriesMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'categories';
	}
}
