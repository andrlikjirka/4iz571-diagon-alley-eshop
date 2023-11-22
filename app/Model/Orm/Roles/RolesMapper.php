<?php

namespace App\Model\Orm\Roles;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class RolesMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'roles';
	}
}
