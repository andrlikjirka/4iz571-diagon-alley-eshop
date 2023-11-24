<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use App\PublicModule\Components\UserLoginControl\UserLoginControl;
use App\PublicModule\Components\UserLoginControl\UserLoginControlFactory;
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

    /** @var UserLoginControlFactory  */
    private UserLoginControlFactory $userLoginControlFactory;

	public function createComponentUserLogin(): UserLoginControl
    {
        return $this->userLoginControlFactory->create();
    }

    #region injects
    public function injectUserLoginControlFactory (UserLoginControlFactory $userLoginControlFactory): void
    {
        $this->userLoginControlFactory = $userLoginControlFactory;
    }
    #endregion injects

}