<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Forms\ChangePasswordFormFactory;
use Nette\Application\UI\Form;


/**
 * Class ChangePasswordPresenter
 * @package App\AdminModule\Presenters
 * @author Martin Kovalski
 */
final class ChangePasswordPresenter extends BasePresenter
{
	public function __construct(
		private readonly ChangePasswordFormFactory $changePasswordFormFactory
	) {
		parent::__construct();
	}


	protected function createComponentChangePasswordForm(): Form
	{
		$onSuccess = function (string $message): void {
			$this->flashMessage($message, 'success');
			$this->redirect('Profile:default');
		};

		$onFailure = function (string $message): void {
			$this->flashMessage($message, 'danger');
			$this->redirect('this');
		};

		return $this->changePasswordFormFactory->create($onSuccess, $onFailure);
	}
}