<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\Http\Session;
use Nette\Security\User;


/**
 * Class BasePresenter
 * @package App\AdminModule\Presenters
 * @author Martin Kovalski
 */
abstract class BasePresenter extends Presenter
{
	/** @var User @inject */
	public User $user;

	/** @var Session @inject */
	public Session $session;

	/**
	 * @throws AbortException
	 */
	public function handleLogOut(): void
	{
		// Destroy session and clear user identity
		$this->session->destroy();
		$this->getUser()->logout(true);

		// Redirect with flash message
		$this->flashMessage('Uživatel byl úspěšně odhlášen', 'success');
		$this->redirect('Homepage:default');
	}
}