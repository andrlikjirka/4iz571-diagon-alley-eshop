<?php

declare(strict_types=1);

namespace App\Components\UserProfileControl;

use App\Model\Facades\UsersFacade;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Security\User;
use stdClass;


/**
 * Class UserProfileControl
 * @package App\Components\UserProfileControl
 * @author Martin Kovalski
 *
 * @property-read Template|stdClass $template
 */
class UserProfileControl extends Control
{
	public function __construct(
		private readonly User $user,
		private readonly UsersFacade $usersFacade
	){}


	public function render(): void
	{
		$this->template->profile = $this->usersFacade->getUser($this->user->getId());
		$this->template->render(__DIR__ . '/templates/' . 'default.latte');
	}

}