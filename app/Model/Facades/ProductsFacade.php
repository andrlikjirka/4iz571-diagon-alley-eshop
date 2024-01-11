<?php

declare(strict_types=1);

namespace App\Model\Facades;


use App\Model\Orm\Categories\Category;
use App\Model\Orm\FavouriteProducts\FavouriteProduct;
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
        $categoriesRecursively = $category->childrenShowedRecursively;
        $categoriesRecursively[] = $category;
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

    public function findShowedProductsTotalCount()
    {
        return $this->orm->products->findBy(['showed' => true])->count();
    }

	public function getFavouriteProductsCountByUser(int $userId): int
	{
		return $this->orm->favouriteProducts->findBy(['user' => $userId])->countStored();
	}

	/**
	 * @return ICollection<FavouriteProduct>
	 */
	public function getFavouriteProductsByUser(int $userId): ICollection
	{
		return $this->orm->favouriteProducts->findBy(['user' => $userId]);
	}

	public function addProductToFavourites(int $userId, int $productId): void
	{
		$favouriteProduct = $this->orm->favouriteProducts->getBy(['product' => $productId, 'user' => $userId]);
		if($favouriteProduct) {
			return;
		}

		$favouriteProduct = new FavouriteProduct();
		$favouriteProduct->user = $this->orm->users->getById($userId);
		$favouriteProduct->product = $this->orm->products->getById($productId);
		$this->orm->favouriteProducts->persistAndFlush($favouriteProduct);
	}

	public function isProductFavouriteForUser(int $productId, int $userId): ?FavouriteProduct
	{
		return $this->orm->favouriteProducts->getBy(['product' => $productId, 'user' => $userId]);
	}

	public function removeProductToFavourites(int $userId, int $productId): void
	{
		$favouriteProduct = $this->orm->favouriteProducts->getBy(['product' => $productId, 'user' => $userId]);
		if($favouriteProduct) {
			$this->orm->favouriteProducts->removeAndFlush($favouriteProduct);
		}
	}

	public function getProductBySlug(string $slug): Product
	{
		return $this->orm->products->getByChecked(['slug' => $slug]);
	}

	/**
	 * @return ICollection<Product>
	 */
	public function getAllProducts(): ICollection
	{
		return $this->orm->products->findBy([
			'deleted' => false,
			'showed' => true,
		])->orderBy('name');
	}
}