<?php

declare(strict_types=1);

namespace App\Model\Orm\Roles;

use Nextras\Orm\Repository\Repository;

class RolesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Role::class];
	}
}
