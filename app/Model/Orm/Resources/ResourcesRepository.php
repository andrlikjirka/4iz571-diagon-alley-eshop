<?php

declare(strict_types=1);

namespace App\Model\Orm\Resources;

use Nextras\Orm\Repository\Repository;

class ResourcesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Resource::class];
	}
}
