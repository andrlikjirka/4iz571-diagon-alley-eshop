<?php

namespace App\Model\Orm;

use App\Model\Orm\Addresses\AddressesRepository;
use App\Model\Orm\ForgottenPasswords\ForgottenPasswordsRepository;
use App\Model\Orm\Orders\OrdersRepository;
use App\Model\Orm\Permissions\PermissionsRepository;
use App\Model\Orm\Products\ProductsRepository;
use App\Model\Orm\Resources\ResourcesRepository;
use App\Model\Orm\Roles\RolesRepository;
use App\Model\Orm\Users\UsersRepository;
use Nextras\Orm\Model\Model;

/**
 * @property-read AddressesRepository $addresses
 * @property-read ForgottenPasswordsRepository $forgottenPasswords
 * @property-read OrdersRepository $orders
 * @property-read PermissionsRepository $permissions
 * @property-read ProductsRepository $products
 * @property-read ResourcesRepository $resources
 * @property-read RolesRepository $roles
 * @property-read UsersRepository $users
 */
class Orm extends Model
{
}
