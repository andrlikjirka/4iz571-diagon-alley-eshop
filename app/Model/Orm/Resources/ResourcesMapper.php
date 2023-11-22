<?php

declare(strict_types=1);

namespace App\Model\Orm\Resources;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class ResourcesMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'resources';
	}
}
