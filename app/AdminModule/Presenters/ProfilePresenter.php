<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Components\UserProfileControl\UserProfileControl;
use App\Components\UserProfileControl\UserProfileControlFactory;


/**
 * Class ProfilePresenter
 * @package App\AdminModule\Presenters
 * @author Martin Kovalski
 */
final class ProfilePresenter extends BasePresenter
{
	public function __construct(
		private readonly UserProfileControlFactory $userProfileControlFactory
	) {
		parent::__construct();
	}

	protected function createComponentUserProfile(): UserProfileControl
	{
		return $this->userProfileControlFactory->create();
	}
}