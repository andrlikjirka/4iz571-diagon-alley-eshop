<?php

namespace App\Model\Facades;

use App\Model\Api\Facebook\FacebookUser;
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
use Nette\Security\SimpleIdentity;
use Nextras\Dbal\Drivers\Exception\QueryException;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
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
     * @return \Nextras\Orm\Entity\IEntity
     * @throws Exception
     */
    public function getUser(int $id): IEntity|User
    {
        return $this->orm->users->getByIdChecked($id);
    }

    /**
     * Metoda pro načtení jednoho uživatele podle emailu
     * @param string $email
     * @return User
     * @throws Exception
     */
    public function getUserByEmail(string $email): IEntity|User
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
	 * Metoda pro načtení uživatelské role podle názvu
	 * @param string $name
	 * @return IEntity|Role
	 */
    public function getRoleByName(string $name): IEntity|Role
    {
        return $this->orm->roles->getBy(['name' => $name]);
    }

    /**
     * Metoda pro načtení uživatelské role podle id
     * @param string $name
     * @return IEntity
     */
    public function getRoleById(int $id): IEntity|Role
    {
        return $this->orm->roles->getBy(['id' => $id]);
    }

	/**
	 * @return ICollection<Role>
	 */
	public function findRoles(): ICollection
	{
		return $this->orm->roles->findAll();
	}

    public function findRolesPairs(): array
    {
        return $this->orm->roles->findAll()->fetchPairs('id', 'name');
    }

	/**
	 * @return ICollection<Permission>
	 */
	public function findPermissions(): ICollection
	{
		return $this->orm->permissions->findAll();
	}

    public function findCustomersTotalCount(): int
    {
        return $this->orm->users->findBy(['role->name' => 'customer'])->count();
    }

    public function findAllUsers()
    {
        return $this->orm->users->findBy(['deleted' => 0]);
    }

	/**
	 * Metoda pro nalezení či zaregistrování uživatele podle facebookId, která vrací SimpleIdentity použitelnou pro přihlášení uživatele
	 * @param FacebookUser $facebookUser
	 * @return SimpleIdentity
	 * @throws Exception
	 */
	public function getFacebookUserIdentity(FacebookUser $facebookUser): SimpleIdentity
	{
		/*
		 * 1. zkusíme najít uživatele podle facebookId
		 * 2. pokud nebyl uživatel nalezen, zkusíme jej najít podle emailu (a uložíme k němu facebookId)
		 * 3. pokud ani tak nebyl uživatel nalezen, vytvoříme nového
		 * 4. vygenerujeme SimpleIdentity pomocí $this->getUserIdentity
	 	 */

		try {
			$user = $this->getUserByFacebookId($facebookUser->facebookUserId);
		} catch (Exception $e) {
			// uživatele se nepovedlo najít podle facebookID
			try {
				// uživatel existuje
				$user = $this->getUserByEmail($facebookUser->email);
				$user->facebookId = $facebookUser->facebookUserId;
				$this->saveUser($user);
			} catch (Exception $ex) {
				// uživatel neexistuje
				$user = new User();
				$user->email = $facebookUser->email;
				$user->name = $facebookUser->name;
				$user->role = $this->getRoleById(4); // nastavíme roli 'customer'
				$user->facebookId = $facebookUser->facebookUserId;
				$this->saveUser($user);
			}
		}
		return $this->getUserIdentity($user);
	}

	public function getUserByFacebookId($facebookId): User
	{
		return $this->orm->users->getByChecked(['facebookId' => $facebookId]);
	}

	/**
	 * Metoda vracející "přihlašovací identitu" pro daného uživatele
	 * @param User $user
	 * @return SimpleIdentity
	 */
	public function getUserIdentity(User $user): SimpleIdentity
	{
		//příprava rolí
		$roles = ['authenticated'];
		if (!empty($user->role)) {
			$roles[] = $user->role->name;
		}
		//vytvoření a vrácení SimpleIdentity
		return new SimpleIdentity($user->id, $roles, ['name' => $user->name, 'email' => $user->email]);
	}

}