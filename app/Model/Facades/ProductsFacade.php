<?php

declare(strict_types=1);

namespace App\Model\Facades;


use App\Model\Orm\Categories\Category;
use App\Model\Orm\Orm;
use App\Model\Orm\ProductPhotos\ProductPhoto;
use App\Model\Orm\Products\Product;
use App\Model\Orm\Reviews\Review;
use Exception;
use Nette\Utils\FileSystem;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Tracy\Debugger;

/**
 * Class ProductsFacade
 * @package App\Model\Facades
 * @author Martin Kovalski
 */
class ProductsFacade
{
    public function __construct(
        private readonly Orm $orm
    )
    {
    }

    public function getProduct(int $id): IEntity|Product
    {
        return $this->orm->products->getByIdChecked($id);
    }

    /**
     * @throws Exception
     */
    public function saveProduct(Product $product): void
    {
        try {
            $this->orm->products->persistAndFlush($product);
        } catch (Exception $e) {
            Debugger::log($e);
            $this->orm->products->getMapper()->rollback();
            throw new Exception('Produkt se nepodařilo uložit');
        }
    }

    public function findShowedProductsByCategory(?Category $category): ICollection|array
    {
        return $this->orm->products->findBy(['category' => $category, 'showed' => true]);
    }

    public function findShowedProductsByCategoryRecursively(?Category $category): ICollection|array
    {
        $categoriesRecursively = array();
        $stack = [$category];
        while (!empty($stack)) {
            $currentCategory = array_pop($stack);
            $categoriesRecursively[] = $currentCategory;
            foreach ($currentCategory->childrenShowed as $child) {
                $stack[] = $child;
            }
        }
        return $this->orm->products->findBy(['category' => $categoriesRecursively, 'showed' => true]);
    }

    /**
     * @return ICollection<Product>
     */
    public function findAllProducts(): ICollection
    {
        return $this->orm->products->findBy(['deleted' => 0]);
    }

    public function findAllShowedProducts(): ICollection|array
    {
        return $this->orm->products->findBy(['showed' => true]);
    }

	public function getProductPhotoByName(string $fileName): ?ProductPhoto
	{
		return $this->orm->productPhotos->getBy(['name' => $fileName]);
	}

	public function saveProductPhoto(ProductPhoto $productPhoto): void
	{
		try {
			$this->orm->productPhotos->persistAndFlush($productPhoto);
		} catch (Exception $e) {
			Debugger::log($e);
			$this->orm->productPhotos->getMapper()->rollback();
			throw new Exception('Fotografii se nepodařilo uložit');
		}
	}

	public function deleteProductPhoto(ProductPhoto $productPhoto): void
	{
		FileSystem::delete(__DIR__ . '/../../../www/uploads/products/' . $productPhoto->name);
		$this->orm->productPhotos->removeAndFlush($productPhoto);
	}

}