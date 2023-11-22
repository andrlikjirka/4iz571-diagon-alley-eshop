<?php

declare(strict_types=1);

namespace App\Model\Orm\Permissions;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class PermissionsMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'permissions';
	}
}
