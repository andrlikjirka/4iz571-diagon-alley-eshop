<?php

declare(strict_types=1);

namespace App\PublicModule\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nextras\FormsRendering\Renderers\Bs5FormRenderer;


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
        $form->setRenderer(new Bs5FormRenderer());

		return $form;
	}
}
