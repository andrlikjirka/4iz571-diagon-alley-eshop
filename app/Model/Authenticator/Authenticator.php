<?php

declare(strict_types=1);

namespace App\Model\Authenticator;

use App\Model\Facades\UsersFacade;
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
		private readonly UsersFacade $usersFacade,
		private readonly Passwords $passwords,
	) {}

	public function authenticate(string $email, string $password): IIdentity
	{
		try {
			$user = $this->usersFacade->getUserByEmail($email);
		} catch (Exception $e) {
			throw new AuthenticationException('Uživatelský účet neexistuje.');
		}

		if($this->passwords->verify($password, $user->password)) {
			return $this->usersFacade->getUserIdentity($user);
		} else {
			throw new AuthenticationException('Chybná kombinace e-mailu a hesla.');
		}
	}
}