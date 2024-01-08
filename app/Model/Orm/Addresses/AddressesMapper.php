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

	public function deleteOldAddresses(int $userId, array $notDeletedAddresses): void
	{
		$this->connection->query('UPDATE addresses SET %set WHERE user_id = %i AND address_id NOT IN %i[]', ['deleted' => 1], $userId, $notDeletedAddresses);
	}
}
