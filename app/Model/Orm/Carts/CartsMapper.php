<?php

declare(strict_types=1);

namespace App\Model\Orm\Carts;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class CartsMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'carts';
	}
}
