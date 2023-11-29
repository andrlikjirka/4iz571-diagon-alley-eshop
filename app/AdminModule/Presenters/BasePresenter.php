<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\AdminNavbarControl\AdminNavbarControl;
use App\AdminModule\Components\AdminNavbarControl\AdminNavbarControlFactory;
use App\Components\UserLoginControl\UserLoginControlFactory;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Http\Session;
use Nette\Security\User;
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

    private UserLoginControlFactory $userLoginControlFactory;

    private AdminNavbarControlFactory $adminNavbarControlFactory;

    public function createComponentUserLogin()
    {
        return $this->userLoginControlFactory->create();
    }

    public function createComponentAdminNavbar(): AdminNavbarControl
    {
        return $this->adminNavbarControlFactory->create();
    }

    #region injects
    public function injectUserLoginControlFactory(UserLoginControlFactory $userLoginControlFactory)
    {
        $this->userLoginControlFactory = $userLoginControlFactory;
    }

    public function injectNavbarControlFactory (AdminNavbarControlFactory $adminNavbarControlFactory): void
    {
        $this->adminNavbarControlFactory = $adminNavbarControlFactory;
    }
    #endregion injects
}