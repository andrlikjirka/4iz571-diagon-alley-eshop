<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\AdminNavbarControl\AdminNavbarControl;
use App\AdminModule\Components\AdminNavbarControl\AdminNavbarControlFactory;
use App\Components\UserLoginControl\UserLoginControl;
use App\Components\UserLoginControl\UserLoginControlFactory;
use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Http\Request;
use Nette\Http\Session;
use Nette\Security\User;
use Nette\Security\UserStorage;
use stdClass;


/**
 * Class BasePresenter
 * @package App\AdminModule\Presenters
 * @author Martin Kovalski
 *
 * @property-read Template|stdClass $template
 */
abstract class BasePresenter extends Presenter
{
	/** @var User @inject */
	public User $user;

	/** @var Session @inject */
	public Session $session;

	/** @var Request @inject */
	public Request $request;

    private UserLoginControlFactory $userLoginControlFactory;

    private AdminNavbarControlFactory $adminNavbarControlFactory;

	/**
	 * @throws BadRequestException
	 * @throws AbortException
	 */
	public function startup(): void
	{
        parent::startup();

		// Check if user is logged in
		if($this->getUser()->isLoggedIn()) {

			// If admin then allowed everything
			if($this->getUser()->isInRole('admin')) {
				return;
			}

			$presenter = ""; //TODO
			$action = "";

			if (!$this->getUser()->isAllowed($presenter, $action)) {
				$this->error('K této stránce nemáte oprávnění',403);
			}
		} else {
			// Logout reason
			if($this->getUser()->getLogoutReason() === UserStorage::LOGOUT_INACTIVITY) {
				$this->flashMessage('Proběhlo odhlášení z důvodu neaktivity');
			}

			// Continue message
			$this->flashMessage('Pro pokračování je potřeba se přihlásit', 'warning');

			// Redirect no logged-in user to login form
			$this->redirect(':Public:LogIn:default', ['backlink' => $this->storeRequest()]);
		}
    }

    public function createComponentUserLogin(): UserLoginControl
	{
        return $this->userLoginControlFactory->create();
    }

    public function createComponentAdminNavbar(): AdminNavbarControl
    {
        return $this->adminNavbarControlFactory->create();
    }

    #region injects
    public function injectUserLoginControlFactory(UserLoginControlFactory $userLoginControlFactory): void
	{
        $this->userLoginControlFactory = $userLoginControlFactory;
    }

    public function injectNavbarControlFactory (AdminNavbarControlFactory $adminNavbarControlFactory): void
    {
        $this->adminNavbarControlFactory = $adminNavbarControlFactory;
    }
    #endregion injects
}