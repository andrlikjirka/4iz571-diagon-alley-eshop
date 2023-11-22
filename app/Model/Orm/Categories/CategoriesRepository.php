<?php

declare(strict_types=1);

namespace App\Model\Orm\Categories;

use Nextras\Orm\Repository\Repository;

class CategoriesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Category::class];
	}
}
