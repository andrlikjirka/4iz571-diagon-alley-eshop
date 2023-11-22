<?php

declare(strict_types=1);

namespace App\Model\Orm\Reviews;

use Nextras\Orm\Repository\Repository;

class ReviewsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Review::class];
	}
}
