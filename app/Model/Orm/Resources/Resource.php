<?php

declare(strict_types=1);

namespace App\Model\Orm\Resources;

use App\Model\Orm\Permissions\Permission;
use Nextras\Orm\Entity\Entity;


/**
 * @property int $id {primary}
 * @property string $name
 * @property Permission[] $permissions {1:m Permission::$resource}
 */
class Resource extends Entity
{
}
