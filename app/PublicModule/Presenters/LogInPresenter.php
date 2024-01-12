<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;


use App\Model\Api\Facebook\FacebookApi;
use App\Model\Facades\UsersFacade;
use App\Model\Orm\Roles\Role;
use App\PublicModule\Forms\LogInFormFactory;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Application\UI\InvalidLinkException;

/**
 * Class LogInPresenter
 * @package App\PublicModule\Presenters
 */
class LogInPresenter extends BasePresenter
{
	/** @persistent */
	public string $backlink = '';

	public function __construct(
		private readonly LogInFormFactory $logInFormFactory,
		private readonly FacebookApi $facebookApi,
		private readonly UsersFacade $usersFacade
	) {
		parent::__construct();
	}

    public function beforeRender(): void
    {
        $this->setLayout('loginRegistrationLayout');
    }

    /**
	 * @throws AbortException
	 */
	public function actionDefault(): void
	{
		if ($this->user->isLoggedIn()) {
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:default');
		}
	}

	/**
	 * Akce pro přihlášení pomocí Facebooku
	 * @param bool $callback
	 * @throws AbortException
	 * @throws InvalidLinkException
	 */
	public function actionFacebookLogin(bool $callback = false): void
	{
		if ($callback) {
			#region návrat z Facebooku
			try {
				$facebookUser = $this->facebookApi->getFacebookUser(); //v proměnné $facebookUser máme facebookId, email a jméno uživatele => jdeme jej přihlásit

				//necháme si vytvořit identitu uživatele
				$userUdentity = $this->usersFacade->getFacebookUserIdentity($facebookUser);

				//přihlásíme uživatele
				$this->user->login($userUdentity);

			} catch (\Exception $e) {
				$this->flashMessage('Přihlášení pomocí Facebooku se nezdařilo.', 'error');
				$this->redirect('Homepage:default');
			}

			$this->redirect('Homepage:default');
			#endregion návrat z Facebooku
		} else {
			#region přesměrování na přihlášení pomocí Facebooku
			$backlink = $this->link('//LogIn:facebookLogin', ['callback' => true]);
			//exit(var_dump($backlink));
			$facebookLoginLink = $this->facebookApi->getLoginUrl($backlink);
			$this->redirectUrl($facebookLoginLink);
			#endregion přesměrování na přihlášení pomocí Facebooku
		}
	}

	protected function createComponentLogInForm(): Form
	{
		$onSuccess = function (string $message, Role $role): void {
			$this->flashMessage($message, 'success');
			$this->restoreRequest($this->backlink);

			if($role->name == 'customer') {
				$this->redirect(':Public:Homepage:default');
			} else {
				$this->redirect(':Admin:Dashboard:default');
			}
		};

		$onFailure = function (string $message): void {
			$this->flashMessage($message, 'danger');
			$this->redirect('this');
		};

		return $this->logInFormFactory->create($onSuccess, $onFailure);
	}
}