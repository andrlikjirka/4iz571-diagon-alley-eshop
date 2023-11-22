<?php

namespace App\Model\Orm\Users;

use App\Model\Orm\AbstractRepository;

class UsersRepository extends AbstractRepository
{
	public static function getEntityClassNames(): array
	{
		return [Users::class];
	}
}
