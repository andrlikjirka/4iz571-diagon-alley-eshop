<?php

declare(strict_types=1);

namespace App\Model\Orm\Addresses;

use Nextras\Orm\Repository\Repository;

/**
 * @method void deleteOldAddresses(int $userId, array $notDeletedAddresses)
 */
class AddressesRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Address::class];
	}
}
