<?php

declare(strict_types=1);

namespace App\PublicModule\Forms;

use App\Model\Authenticator\Authenticator;
use Closure;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Nette\Utils\ArrayHash;


/**
 * Class LogInFormFactory
 * @package App\PublicModule\Forms
 * @author Martin Kovalski
 */
class LogInFormFactory
{
	private Closure $onSuccess;
	private Closure $onFailure;

	public function __construct(
		private readonly FormFactory $formFactory,
		private readonly User $user,
		private readonly Authenticator $authenticator,
	) {}

	public function create(callable $onSuccess, callable $onFailure): Form
	{
		$form = $this->formFactory->create();

		$form->addEmail('email', 'E-mail')
			->setRequired()
			->setHtmlAttribute('placeholder', 'E-mail')
			->setHtmlAttribute('autofocus');

		$form->addPassword('password', 'Heslo')
			->setRequired()
			->setHtmlAttribute('placeholder', 'Heslo');

		$form->addSubmit('login', 'Přihlásit se');

		$form->onSuccess[] = $this->formSucceeded(...);

		$this->onSuccess = $onSuccess;
		$this->onFailure = $onFailure;

		return $form;
	}

	private function formSucceeded(Form $form, ArrayHash $values): void
	{
		$this->user->setAuthenticator($this->authenticator);

		try {
			$this->user->login($values->email, $values->password);
		} catch (AuthenticationException $e) {
			($this->onFailure)($e->getMessage());
			return;
		}

		($this->onSuccess)('Uživatel byl přihlášen');
	}
}