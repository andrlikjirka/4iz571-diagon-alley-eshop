<?php

namespace App\Model\Orm\Roles;

use App\Model\Orm\AbstractRepository;

class RolesRepository extends AbstractRepository
{
	public static function getEntityClassNames(): array
	{
		return [Roles::class];
	}
}
