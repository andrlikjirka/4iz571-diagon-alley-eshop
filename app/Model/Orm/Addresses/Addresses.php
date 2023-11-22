<?php

namespace App\Model\Orm\Addresses;

use App\Model\Orm\AbstractEntity;
use App\Model\Orm\Users\Users;

/**
 * @property int $addressId
 * @property string $street
 * @property string $city
 * @property string $zip
 * @property Users $userId {??? Users::$???}
 */
class Addresses extends AbstractEntity
{
}
