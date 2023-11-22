<?php

namespace App\Model\Orm\ForgottenPasswords;

use App\Model\Orm\AbstractMapper;

class ForgottenPasswordsMapper extends AbstractMapper
{
	public function getTableName(): string
	{
		return 'forgotten_passwords';
	}
}
