<?php

namespace App\Model\Facades;

use App\Model\Orm\Orm;
use App\Model\Orm\Roles\RolesRepository;
use App\Model\Orm\Users\User;
use App\Model\Orm\Users\UsersRepository;
use Cassandra\Exception\UnauthorizedException;

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
     * @throws \Exception
     */
    public function saveUser(User $user): void
    {
        $result = (bool)$this->orm->users->persistAndFlush($user);
        if (!$result) {
            throw new \Exception();
        }
    }

    //TODO: doplnit, v Authenticatoru (a možná i jinde) nahradit přímé volání new SimpleIdentity pomocí této metody
    public function getUserIdentity(User $user) {

    }

}