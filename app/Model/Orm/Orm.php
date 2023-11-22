<?php

declare(strict_types=1);

namespace App\Model\Orm;

use App\Model\Orm\Addresses\AddressesRepository;
use App\Model\Orm\CartItems\CartItemsRepository;
use App\Model\Orm\Carts\CartsRepository;
use App\Model\Orm\Categories\CategoriesRepository;
use App\Model\Orm\FavouriteProducts\FavouriteProductsRepository;
use App\Model\Orm\ForgottenPasswords\ForgottenPasswordsRepository;
use App\Model\Orm\OrderItems\OrderItemsRepository;
use App\Model\Orm\Orders\OrdersRepository;
use App\Model\Orm\Permissions\PermissionsRepository;
use App\Model\Orm\ProductPhotos\ProductPhotosRepository;
use App\Model\Orm\Products\ProductsRepository;
use App\Model\Orm\Resources\ResourcesRepository;
use App\Model\Orm\Reviews\ReviewsRepository;
use App\Model\Orm\Roles\RolesRepository;
use App\Model\Orm\Users\UsersRepository;
use Nextras\Orm\Model\Model;


/**
 * @property-read AddressesRepository $addresses
 * @property-read CartItemsRepository $cartItems
 * @property-read CartsRepository $carts
 * @property-read CategoriesRepository $categories
 * @property-read FavouriteProductsRepository $favouriteProducts
 * @property-read ForgottenPasswordsRepository $forgottenPasswords
 * @property-read OrderItemsRepository $orderItems
 * @property-read OrdersRepository $orders
 * @property-read PermissionsRepository $permissions
 * @property-read ProductPhotosRepository $productPhotos
 * @property-read ProductsRepository $products
 * @property-read ResourcesRepository $resources
 * @property-read ReviewsRepository $reviews
 * @property-read RolesRepository $roles
 * @property-read UsersRepository $users
 */
class Orm extends Model
{
}
