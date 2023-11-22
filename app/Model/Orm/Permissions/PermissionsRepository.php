<?php

namespace App\Model\Orm\Permissions;

use App\Model\Orm\AbstractRepository;

class PermissionsRepository extends AbstractRepository
{
	public static function getEntityClassNames(): array
	{
		return [Permissions::class];
	}
}
