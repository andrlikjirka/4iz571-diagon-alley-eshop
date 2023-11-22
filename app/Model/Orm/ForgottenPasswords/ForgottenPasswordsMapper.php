<?php

declare(strict_types=1);

namespace App\Model\Orm\ForgottenPasswords;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class ForgottenPasswordsMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'forgotten_passwords';
	}
}
