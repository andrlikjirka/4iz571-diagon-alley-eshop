<?php

declare(strict_types=1);

namespace App\Model\Authenticator;

use App\Model\Orm\Orm;
use Exception;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;


/**
 * Class Authenticator
 * @package App\Model\Authenticator
 * @author Martin Kovalski
 */
class Authenticator implements \Nette\Security\Authenticator
{
	public function __construct(
		private readonly Orm $orm,
		private readonly Passwords $passwords,
	) {}

	public function authenticate(string $email, string $password): IIdentity
	{
		try {
			$user = $this->orm->users->getByEmail($email);
		} catch (Exception $e) {
			throw new AuthenticationException('Uživatelský účet neexistuje.');
		}

		if($this->passwords->verify($password, $user->password)) {
			return new SimpleIdentity($user->id, $user->role->name, ['name' => $user->name, 'email' => $user->email]);
		} else {
			throw new AuthenticationException('Chybná kombinace e-mailu a hesla.');
		}
	}
}