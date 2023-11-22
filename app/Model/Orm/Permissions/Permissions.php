<?php

namespace App\Model\Orm\Permissions;

use App\Model\Orm\AbstractEntity;
use App\Model\Orm\Resources\Resources;
use App\Model\Orm\Roles\Roles;

/**
 * @property int $permissionId
 * @property Roles $roleId {??? Roles::$???}
 * @property Resources $resourceId {??? Resources::$???}
 * @property string $action
 * @property string $type {default self::TYPE_allow} {enum self::TYPE_*}
 */
class Permissions extends AbstractEntity
{
	public const TYPE_ALLOW = 'ALLOW';
	public const TYPE_DENY = 'DENY';
}
