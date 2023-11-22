<?php

namespace App\Model\Orm\Resources;

use App\Model\Orm\AbstractMapper;

class ResourcesMapper extends AbstractMapper
{
	public function getTableName(): string
	{
		return 'resources';
	}
}
