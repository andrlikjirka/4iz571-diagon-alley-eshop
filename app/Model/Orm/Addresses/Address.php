<?php

declare(strict_types=1);

namespace App\Model\Orm\Addresses;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Users\User;


/**
 * @property int $id {primary}
 * @property string $name
 * @property string $street
 * @property string $city
 * @property string $zip
 * @property ?User $user {m:1 User::$allAddresses}
 * @property int $deleted {default 0}
 */
class Address extends Entity
{
}
