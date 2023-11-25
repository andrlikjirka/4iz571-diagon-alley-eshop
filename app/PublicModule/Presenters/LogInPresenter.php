<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;


use App\PublicModule\Forms\LogInFormFactory;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;

class LogInPresenter extends BasePresenter
{
	/** @persistent */
	public string $backlink = '';

	public function __construct(
		private readonly LogInFormFactory $logInFormFactory
	) {
		parent::__construct();
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

    //TODO: fb login
    public function actionFacebookLogIn(): void
    {}

	protected function createComponentLogInForm(): Form
	{
		$onSuccess = function (string $message): void {
			$this->flashMessage($message, 'success');
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:default');
		};

		$onFailure = function (string $message): void {
			$this->flashMessage($message, 'danger');
			$this->redirect('this');
		};

		return $this->logInFormFactory->create($onSuccess, $onFailure);
	}
}