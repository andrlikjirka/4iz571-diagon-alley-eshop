<?php

namespace App\Model\Orm\Permissions;

use App\Model\Orm\AbstractMapper;

class PermissionsMapper extends AbstractMapper
{
	public function getTableName(): string
	{
		return 'permissions';
	}
}
