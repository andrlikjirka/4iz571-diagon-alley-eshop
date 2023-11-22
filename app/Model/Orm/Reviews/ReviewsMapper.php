<?php

declare(strict_types=1);

namespace App\Model\Orm\Reviews;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

class ReviewsMapper extends DbalMapper
{
	public function getTableName(): string
	{
		return 'reviews';
	}
}
