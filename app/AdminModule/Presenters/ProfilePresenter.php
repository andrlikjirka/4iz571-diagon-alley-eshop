<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Forms\ProfileEditFormFactory;
use App\Components\UserProfileControl\UserProfileControl;
use App\Components\UserProfileControl\UserProfileControlFactory;
use Nette\Application\UI\Form;


/**
 * Class ProfilePresenter
 * @package App\AdminModule\Presenters
 * @author Martin Kovalski
 */
final class ProfilePresenter extends BasePresenter
{
	public function __construct(
		private readonly UserProfileControlFactory $userProfileControlFactory,
		private readonly ProfileEditFormFactory $profileEditFormFactory
	) {
		parent::__construct();
	}

	protected function createComponentUserProfile(): UserProfileControl
	{
		return $this->userProfileControlFactory->create();
	}

	public function actionEdit(): void
	{
		$this->getComponent('profileEditForm')->setDefaults([
			'name' => $this->user->getIdentity()->getData()['name'],
			'email' => $this->user->getIdentity()->getData()['email']
		]);
	}

	protected function createComponentProfileEditForm(): Form
	{
		$onSuccess = function ($message, $changed) {
			if ($changed) {
				// Destroy session and clear user identity
				$this->session->destroy();
				$this->user->logout(true);
				$this->flashMessage($message, 'success');
				$this->redirect(':Public:LogIn:default', ['backlink' => $this->storeRequest()]);
			}
			$this->flashMessage($message, 'success');
			$this->redirect('Profile:default');
		};
		$onFailure = function ($message) {
			$this->flashMessage($message, 'danger');
			$this->redirect('this');
		};

		return $this->profileEditFormFactory->create($onSuccess, $onFailure);
	}
}