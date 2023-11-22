<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use Nette\Application\UI\Presenter;
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

}