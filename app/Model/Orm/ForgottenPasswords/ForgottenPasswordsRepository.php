<?php

namespace App\Model\Orm\ForgottenPasswords;

use App\Model\Orm\AbstractRepository;

class ForgottenPasswordsRepository extends AbstractRepository
{
	public static function getEntityClassNames(): array
	{
		return [ForgottenPasswords::class];
	}
}
