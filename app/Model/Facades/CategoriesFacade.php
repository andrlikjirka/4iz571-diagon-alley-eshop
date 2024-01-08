<?php

namespace App\Model\Facades;

use App\Model\Orm\Categories\Category;
use App\Model\Orm\Orm;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Tracy\Debugger;

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

    public function findAllCategories(): ICollection|array
    {
        return $this->orm->categories->findBy(['deleted' => false]);
    }

    public function findShowedCategoryTopParents(): ICollection|array
    {
        return $this->orm->categories->findBy(['showed' => true, 'parent' => null]);
    }

    public function saveCategory(Category $category): void
    {
        try {
            $this->orm->categories->persistAndFlush($category);
        } catch (\Exception $e) {
            Debugger::log($e);
            $this->orm->categories->getMapper()->rollback();
            throw new \Exception('Kategorii se nepodařilo uložit.');
        }
    }

    public function deleteCategory(Category $category): void
    {
        try{
            $this->orm->removeAndFlush($category);
        } catch (\Exception $e) {
            Debugger::log($e);
            $this->orm->categories->getMapper()->rollback();
            throw new \Exception('Kategorii se nepodařilo smazat.');
        }
    }

	public function findAllCategoriesPairs(): array
	{
		return $this->orm->categories->findAll()->fetchPairs('id', 'name');
	}

	public function getCategory(int $categoryId): ?Category
	{
		return $this->orm->categories->getById($categoryId);
	}

}