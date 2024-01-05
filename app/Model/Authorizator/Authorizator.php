<?php

declare(strict_types=1);

namespace App\Model\Authorizator;

use App\Model\Facades\UsersFacade;
use Nette\Security\Permission;


/**
 * Class Authorizator
 * @package App\Model\Authorizator
 * @author Martin Kovalski
 */
class Authorizator extends Permission
{
	public function __construct(
		UsersFacade $usersFacade
	)
	{
		foreach ($usersFacade->findResources() as $resource) {
			$this->addResource($resource->name);
		}

		foreach ($usersFacade->findRoles() as $role) {
			$this->addRole($role->name);
		}

		foreach ($usersFacade->findPermissions() as $permission) {
			if ($permission->type == 'allow') {
				$this->allow($permission->role->name, $permission->resource->name, $permission->action ?? null);
			} else {
				$this->deny($permission->role->name, $permission->resource->name, $permission->action ?? null);
			}
		}
	}

	public function isAllowed($role = self::ALL, $resource = self::ALL, $privilege = self::ALL): bool
	{
		return parent::isAllowed($role, $resource, $privilege);
	}
}