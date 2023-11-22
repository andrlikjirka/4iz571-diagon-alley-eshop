<?php

declare(strict_types=1);

namespace App\Model\Orm\Carts;

use Nextras\Orm\Repository\Repository;

class CartsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Cart::class];
	}
}
