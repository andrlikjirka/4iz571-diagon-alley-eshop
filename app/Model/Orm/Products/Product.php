<?php

declare(strict_types=1);

namespace App\Model\Orm\Products;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Categories\Category;
use App\Model\Orm\ProductPhotos\ProductPhoto;
use App\Model\Orm\Reviews\Review;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property int $id {primary}
 * @property string $name
 * @property string $summary
 * @property string|NULL $description
 * @property DateTimeImmutable $created {default now}
 * @property DateTimeImmutable|NULL $updated
 * @property int $stock {default 0}
 * @property ?Category $category {m:1 Category::$products}
 * @property bool $showed {default 0}
 * @property bool $deleted {default 0}
 * @property-read ?ProductPhoto $firstProductPhoto {virtual}
 * @property OneHasMany|ProductPhoto[] $productPhotos {1:m ProductPhoto::$product}
 * @property-read ICollection|Review[] $reviewsOrderedByDate {virtual}
 * @property OneHasMany|Review[] $reviews {1:m Review::$product}
 * @property int $galleonPrice {default 0}
 * @property int $sicklePrice {default 0}
 * @property int $knutPrice {default 0}
 *
 * @property-read double $avgStars {virtual}
 */
class Product extends Entity
{
    public function getterReviewsOrderedByDate()
    {
        return $this->reviews->toCollection()->orderBy('added', 'DESC');
    }

    public function getterAvgStars(): float
    {
        if (count($this->reviews) > 0) {
            $sum = 0;
            foreach ($this->reviews as $review) {
                $sum += $review->stars;
            }
            return round($sum / count($this->reviews), 1);
        }
        return 0.0;
    }

    public function getterFirstProductPhoto(): ?ProductPhoto
    {
        return $this->productPhotos->toCollection()->fetch();
    }

}
