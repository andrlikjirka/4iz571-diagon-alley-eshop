<?php

namespace App\Model\Orm\Addresses;

use App\Model\Orm\AbstractRepository;

class AddressesRepository extends AbstractRepository
{
	public static function getEntityClassNames(): array
	{
		return [Addresses::class];
	}
}
