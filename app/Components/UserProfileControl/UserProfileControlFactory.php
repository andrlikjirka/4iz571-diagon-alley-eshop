<?php

namespace App\Components\UserProfileControl;

/**
 * Interface UserProfileControlFactory
 * @package App\Components\UserProfileControl
 * @author Martin Kovalski
 */
interface UserProfileControlFactory
{
	public function create(): UserProfileControl;
}