<?php

namespace App\Model\Orm\Resources;

use App\Model\Orm\AbstractRepository;

class ResourcesRepository extends AbstractRepository
{
	public static function getEntityClassNames(): array
	{
		return [Resources::class];
	}
}
