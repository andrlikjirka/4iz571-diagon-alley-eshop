<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use App\Components\UserLoginControl\UserLoginControl;
use App\Components\UserLoginControl\UserLoginControlFactory;
use App\PublicModule\Components\CartControl\CartControl;
use App\PublicModule\Components\CartControl\CartControlFactory;
use App\PublicModule\Components\NavbarControl\NavbarControl;
use App\PublicModule\Components\NavbarControl\NavbarControlFactory;
use Nette\Application\AbortException;
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

    /** @var UserLoginControlFactory  */
    private UserLoginControlFactory $userLoginControlFactory;

    /** @var NavbarControlFactory  */
    private NavbarControlFactory $navbarControlFactory;

    private CartControlFactory $cartControlFactory;

    /**
     * Tovární metoda pro začlenění komponenty UserLoginControl
     * @return UserLoginControl
     */
	public function createComponentUserLogin(): UserLoginControl
    {
        return $this->userLoginControlFactory->create();
    }

    /**
     * Tovární metoda pro začlenění komponenty Navbar
     * @return NavbarControl
     */
    protected function createComponentNavbar(): NavbarControl
    {
        return $this->navbarControlFactory->create();
    }

    protected function createComponentCart(): CartControl
    {
        return $this->cartControlFactory->create();
    }

    #region injects
    public function injectUserLoginControlFactory (UserLoginControlFactory $userLoginControlFactory): void
    {
        $this->userLoginControlFactory = $userLoginControlFactory;
    }

    public function injectNavbarControlFactory (NavbarControlFactory $navbarControlFactory): void
    {
        $this->navbarControlFactory = $navbarControlFactory;
    }

    public function injectCartControlFactory (CartControlFactory $cartControlFactory): void
    {
        $this->cartControlFactory = $cartControlFactory;
    }

    #endregion injects

}