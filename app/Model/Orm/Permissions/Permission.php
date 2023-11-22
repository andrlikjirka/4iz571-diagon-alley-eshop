<?php

declare(strict_types=1);

namespace App\Model\Orm\Permissions;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Resources\Resource;
use App\Model\Orm\Roles\Role;


/**
 * @property int $id {primary}
 * @property Role $role {m:1 Role::$permissions}
 * @property Resource $resource {m:1 Resource::$permissions}
 * @property string $action
 * @property string $type {default self::TYPE_ALLOW} {enum self::TYPE_*}
 */
class Permission extends Entity
{
	public const TYPE_ALLOW = 'ALLOW';
	public const TYPE_DENY = 'DENY';
}
