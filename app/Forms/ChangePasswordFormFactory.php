<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model\Authenticator\Authenticator;
use App\Model\Facades\UsersFacade;
use Closure;
use Exception;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\User;
use Nette\Utils\ArrayHash;


/**
 * Class ChangePasswordFormFactory
 * @package App\Forms
 * @author Martin Kovalski
 */
class ChangePasswordFormFactory
{
	private Closure $onSuccess;
	private Closure $onFailure;

	public function __construct(
		private readonly FormFactory $formFactory,
		private readonly User $user,
		private readonly Authenticator $authenticator,
		private readonly UsersFacade $usersFacade,
		private readonly Passwords $passwords,
	) {}

	public function create(callable $onSuccess, callable $onFailure): Form
	{
		$form = $this->formFactory->create();

		$form->addPassword('oldPassword', 'Staré heslo')
			->setRequired()
			->setHtmlAttribute('placeholder', 'Staré heslo');

		$form->addPassword('newPassword', 'Nové heslo')
			->setRequired()
			->setHtmlAttribute('placeholder', 'Nové heslo')
			->addRule($form::MIN_LENGTH, 'Heslo musí obsahovat minimálně %d znaků', 5);


		$form->addPassword('newPassword2', 'Nové heslo znovu')
			->setRequired()
			->setHtmlAttribute('placeholder', 'Nové heslo')
			->setOmitted()
			->addRule($form::EQUAL, 'Hesla se neshodují',  $form['newPassword']);

		$form->addSubmit('change', 'Změnit heslo');

		$form->onSuccess[] = $this->formSucceeded(...);

		$this->onSuccess = $onSuccess;
		$this->onFailure = $onFailure;

		return $form;
	}

	private function formSucceeded(Form $form, ArrayHash $values): void
	{
		$email = $this->user->getIdentity()->getData()['email'];

		try {
			$this->authenticator->authenticate($email, $values->oldPassword);
		} catch (AuthenticationException $e) {
			($this->onFailure)('Zadali jste špatné heslo');
			return;
		}

		try {
			$user = $this->usersFacade->getUserByEmail($email);
		} catch (Exception $e) {
			($this->onFailure)('Uživatel nebyl nalezen');
			return;
		}

		$user->password = $this->passwords->hash($values->newPassword);

		try {
			$this->usersFacade->saveUser($user);
		} catch (Exception $e) {
			($this->onFailure)('Heslo se nepodařilo změnit');
			return;
		}

		($this->onSuccess)('Heslo bylo úspěšně změněno');
	}
}