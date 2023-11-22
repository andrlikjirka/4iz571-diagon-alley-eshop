<?php

declare(strict_types=1);

namespace App\PublicModule\Forms;

use Nette;
use Nette\Application\UI\Form;


/**
 * Class FormFactory
 * @package App\PublicModule\Forms
 * @author Martin Kovalski
 */
final class FormFactory
{
	public function __construct(
		private readonly Nette\Security\User $user,
	) {
	}

	public function create(): Form
	{
		$form = new Form;

		if ($this->user->isLoggedIn()) {
			$form->addProtection();
		}

		//$form->setHtmlAttribute('class', 'ajax');

		return $form;
	}
}
