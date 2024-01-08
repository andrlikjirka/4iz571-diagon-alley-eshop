<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use App\Components\UserProfileControl\UserProfileControl;
use App\Components\UserProfileControl\UserProfileControlFactory;
use App\Model\Facades\AddressFacade;
use App\Model\Facades\UsersFacade;
use App\PublicModule\Forms\ProfileEditFormFactory;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;


/**
 * Class ProfilePresenter
 * @package App\PublicModule\Presenters
 * @author Martin Kovalski
 */
class ProfilePresenter extends BasePresenter
{
	public function __construct(
		private readonly UserProfileControlFactory $userProfileControlFactory,
		private readonly UsersFacade $usersFacade,
		private readonly ProfileEditFormFactory $profileEditFormFactory,
		private readonly AddressFacade $addressFacade
	) {
		parent::__construct();
	}

	/**
	 * @throws AbortException
	 */
	public function renderDefault(): void
	{
		try {
			$this->template->profile = $this->usersFacade->getUser($this->user->getId());
		} catch (Exception $e) {
			$this->flashMessage('Uživatel nebyl nalezen', 'danger');
			$this->redirect('Homepage:default');
		}
	}

	protected function createComponentUserProfile(): UserProfileControl
	{
		return $this->userProfileControlFactory->create();
	}

	public function actionEdit(): void
	{
		$this->getComponent('profileEditForm')->setDefaults([
			'name' => $this->user->getIdentity()->getData()['name'],
			'email' => $this->user->getIdentity()->getData()['email'],
			'multiplier' => $this->addressFacade->getAddressesPairsByUserId($this->user->getId())
		]);
	}

	protected function createComponentProfileEditForm(): Form
	{
		/**
		 * @throws AbortException
		 */
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

		/**
		 * @throws AbortException
		 */
		$onFailure = function ($message) {
			$this->flashMessage($message, 'danger');
			$this->redirect('this');
		};

		return $this->profileEditFormFactory->create($onSuccess, $onFailure);
	}
}
