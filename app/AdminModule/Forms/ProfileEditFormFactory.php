<?php

declare(strict_types=1);

namespace App\AdminModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\UsersFacade;
use Closure;
use Exception;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Nette\Utils\ArrayHash;


/**
 * Class ProfileEditFormFactory
 * @package App\AdminModule\Forms
 * @author Martin Kovalski
 */
class ProfileEditFormFactory
{
	private Closure $onSuccess;
	private Closure $onFailure;

	public function __construct(
		private readonly FormFactory $formFactory,
		private readonly UsersFacade $usersFacade,
		private readonly User $user
	) {}

	public function create(callable $onSuccess, callable $onFailure): Form
	{
		$form = $this->formFactory->create();

		$form->addText('name', 'Jméno', maxLength: 255)
			->setRequired();

		$form->addEmail('email', 'E-mail')
			->setRequired();

		$form->addSubmit('save', 'Uložit profil');

		$form->onSuccess[] = $this->formSucceeded(...);

		$this->onSuccess = $onSuccess;
		$this->onFailure = $onFailure;

		return $form;
	}

	private function formSucceeded(Form $form, ArrayHash $values): void
	{
		try {
			$user = $this->usersFacade->getUser($this->user->getId());
		} catch (Exception $e) {
			($this->onFailure)('Uživatel nebyl nalezen');
			return;
		}

		$changed = false;

		if($user->email != $values->email) {
			try {
				$this->usersFacade->getUserByEmail($values->email);
				($this->onFailure)('Uživatel s tímto e-mailem již existuje');
				return;
			} catch (Exception $e) {}
			$changed = true;
		}

		if($user->name != $values->name) {
			$changed = true;
		}

		$user->name = $values->name;
		$user->email = $values->email;

		try {
			$this->usersFacade->saveUser($user);
		} catch (Exception $e) {
			($this->onFailure)('Profil se nepodařilo uložit');
			return;
		}

		if($changed) {
			($this->onSuccess)('Profil byl úspěšně uložen, pro pokračování se prosím znovu přihlaste', $changed);
		} else {
			($this->onSuccess)('Profil byl úspěšně uložen', $changed);
		}
	}
}