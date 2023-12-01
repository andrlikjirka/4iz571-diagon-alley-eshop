<?php

namespace App\Model\Facades;

use App\Model\Orm\Orm;
use Nextras\Orm\Collection\ICollection;

class CategoriesFacade
{

    public function __construct(
        private readonly Orm $orm
    )
    {}

    public function getShowedCategories(): ICollection|array
    {
        return $this->orm->categories->findBy(['showed' => true]);
    }

}