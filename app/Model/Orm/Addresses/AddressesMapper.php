<?php

namespace App\Model\Orm\Addresses;

use App\Model\Orm\AbstractMapper;

class AddressesMapper extends AbstractMapper
{
	public function getTableName(): string
	{
		return 'addresses';
	}
}
