<?php

namespace App\Model\Facades;

use App\Model\Orm\Orm;
use App\Model\Orm\Roles\RolesRepository;
use App\Model\Orm\Users\User;
use App\Model\Orm\Users\UsersRepository;

/**
 * Class UsersFacade
 * @package App\Model\Facades
 * @author Jiří Andrlík
 */
class UsersFacade
{
    /**
     * UsersFacade constructor
     * @param Orm $orm
     */
    public function __construct (
       private readonly Orm $orm
    ){}

    /**
     * Metoda pro načtení jednoho uživatele
     * @param int $id
     * @return User
     */
    public function getUser(int $id): User
    {
        return $this->orm->users->getByIdChecked($id);
    }

    /**
     * Metoda pro načtení jednoho uživatele podle emailu
     * @param string $email
     * @return User
     */
    public function getUserByEmail(string $email): User
    {
        return $this->orm->users->getByChecked(['email' => $email]);
    }

    /**
     * Metoda pro uložení uživatele
     * @param User $user
     * @return bool
     */
    public function saveUser(User $user): bool
    {
        return (bool)$this->orm->users->persistAndFlush($user);
    }

    //TODO: doplnit, v Authenticatoru (a možná i jinde) nahradit přímé volání new SimpleIdentity pomocí této metody
    public function getUserIdentity(User $user) {

    }

}