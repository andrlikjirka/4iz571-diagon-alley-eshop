<?php

declare(strict_types=1);

namespace App\Model\Orm\Addresses;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class AddressesMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'addresses';
	}
}
