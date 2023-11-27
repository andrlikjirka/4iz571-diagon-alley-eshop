<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use App\Components\UserProfileControl\UserProfileControl;
use App\Components\UserProfileControl\UserProfileControlFactory;
use App\Model\Facades\UsersFacade;


/**
 * Class ProfilePresenter
 * @package App\PublicModule\Presenters
 * @author Martin Kovalski
 */
class ProfilePresenter extends BasePresenter
{
	public function __construct(
		private readonly UserProfileControlFactory $userProfileControlFactory,
		private readonly UsersFacade $usersFacade
	) {
		parent::__construct();
	}

	public function renderDefault(): void
	{
		$this->template->profile = $this->usersFacade->getUser($this->user->getId());
	}

	protected function createComponentUserProfile(): UserProfileControl
	{
		return $this->userProfileControlFactory->create();
	}
}
