<?php

namespace App\Model\Orm\Roles;

use App\Model\Orm\AbstractMapper;

class RolesMapper extends AbstractMapper
{
	public function getTableName(): string
	{
		return 'roles';
	}
}
