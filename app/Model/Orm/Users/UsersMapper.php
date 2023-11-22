<?php

namespace App\Model\Orm\Users;

use App\Model\Orm\AbstractMapper;

class UsersMapper extends AbstractMapper
{
	public function getTableName(): string
	{
		return 'users';
	}
}
