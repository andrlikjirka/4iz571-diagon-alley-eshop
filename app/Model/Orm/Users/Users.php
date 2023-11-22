<?php

namespace App\Model\Orm\Users;

use App\Model\Orm\AbstractEntity;
use App\Model\Orm\Roles\Roles;

/**
 * @property int $userId
 * @property string $name
 * @property string|NULL $email
 * @property string|NULL $facebookId
 * @property Roles $roleId {??? Roles::$???}
 * @property string|NULL $password
 */
class Users extends AbstractEntity
{
}
