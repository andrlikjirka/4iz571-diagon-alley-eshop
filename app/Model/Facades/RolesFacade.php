<?php

namespace App\Model\Facades;

use App\Model\Orm\Orm;
use App\Model\Orm\Roles\Role;

/**
 * Class RolesFacade
 * @package App\Model\Facades
 * @author Jiří Andrlík
 */
class RolesFacade
{
    /**
     * RolesFacade constructor
     * @param Orm $orm
     */
    public function __construct(
        private readonly Orm $orm
    )
    {}

    /**
     * Metoda pro načtení uživatelské role
     * @param string $name
     * @return Role|null
     */
    public function getRoleByName(string $name): ?Role {
        return $this->orm->roles->getBy(['name' => $name]);
    }

}