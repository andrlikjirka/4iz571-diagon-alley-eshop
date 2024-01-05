<?php

namespace App\Model\Facades;

use App\Model\Orm\Addresses\Address;
use App\Model\Orm\Orm;
use App\Model\Orm\Permissions\Permission;
use App\Model\Orm\Resources\Resource;
use App\Model\Orm\Roles\Role;
use App\Model\Orm\Roles\RolesRepository;
use App\Model\Orm\Users\User;
use App\Model\Orm\Users\UsersRepository;
use Exception;
use Nette\Database\ConstraintViolationException;
use Nextras\Dbal\Drivers\Exception\QueryException;
use Nextras\Orm\Collection\ICollection;
use Tracy\Debugger;

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
     * @throws Exception
     */
    public function getUser(int $id): User
    {
        return $this->orm->users->getByIdChecked($id);
    }

    /**
     * Metoda pro načtení jednoho uživatele podle emailu
     * @param string $email
     * @return User
     * @throws Exception
     */
    public function getUserByEmail(string $email): User
    {
        return $this->orm->users->getByChecked(['email' => $email]);
    }

    /**
     * Metoda pro uložení uživatele
     * @param User $user
     * @throws Exception
     */
    public function saveUser(User $user): void
    {
        try{
            $this->orm->users->persistAndFlush($user);
        } catch (Exception $e) {
            Debugger::log($e);
            $this->orm->users->getMapper()->rollback();
            throw new Exception('Uživatele se nepodařilo uložit.');
        }
    }

    //TODO: doplnit, v Authenticatoru (a možná i jinde) nahradit přímé volání new SimpleIdentity pomocí této metody
    public function getUserIdentity(User $user) {

    }

    public function findUserAddresses(int $userId): ICollection|array
    {
        return $this->orm->addresses->findBy(['user' => $userId, 'deleted' => 0]);
    }

	/**
	 * @return ICollection<Resource>
	 */
	public function findResources(): ICollection
	{
		return $this->orm->resources->findAll();
	}

	/**
	 * @return ICollection<Role>
	 */
	public function findRoles(): ICollection
	{
		return $this->orm->roles->findAll();
	}

	/**
	 * @return ICollection<Permission>
	 */
	public function findPermissions(): ICollection
	{
		return $this->orm->permissions->findAll();
	}

}