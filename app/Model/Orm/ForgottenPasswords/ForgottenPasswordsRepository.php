<?php

declare(strict_types=1);

namespace App\Model\Orm\ForgottenPasswords;

use Nextras\Orm\Repository\Repository;

class ForgottenPasswordsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [ForgottenPassword::class];
	}
}
