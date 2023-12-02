<?php

namespace App\Model\Facades;

use App\Model\Orm\Categories\Category;
use App\Model\Orm\Orm;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;

class CategoriesFacade
{

    public function __construct(
        private readonly Orm $orm
    )
    {}

    public function getCategoryById(int $categoryId): IEntity|Category
    {
        return $this->orm->categories->getByIdChecked($categoryId);
    }

    public function getShowedCategories(): ICollection|array
    {
        return $this->orm->categories->findBy(['showed' => true]);
    }

}