<?php

declare(strict_types=1);

namespace App\PublicModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\AddressFacade;
use App\Model\Facades\UsersFacade;
use App\Model\Orm\Addresses\Address;
use Closure;
use Exception;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Security\User;
use Nette\Utils\ArrayHash;


/**
 * Class ProfileEditFormFactory
 * @package App\PublicModule\Forms
 * @author Martin Kovalski
 */
class ProfileEditFormFactory
{
	private Closure $onSuccess;
	private Closure $onFailure;

	public function __construct(
		private readonly FormFactory $formFactory,
		private readonly UsersFacade $usersFacade,
		private readonly User $user,
		private readonly AddressFacade $addressFacade
	) {}

	public function create(callable $onSuccess, callable $onFailure): Form
	{
		$form = $this->formFactory->create();

		$form->addText('name', 'Jméno', maxLength: 255)
			->setRequired();

		$form->addEmail('email', 'E-mail')
			->setRequired();

		//users addresses
		$copies = 1;
		$maxCopies = 10;

		$multiplier = $form->addMultiplier('multiplier', function (Container $container, \Nette\Forms\Form $form) {
			$container->addHidden('addressId');
			$container->addText('name', 'Jméno')
				->setRequired();
			$container->addText('street', 'Ulice a č.p.')
				->setRequired();
			$container->addText('city', 'Město')
				->setRequired();
			$container->addText('zip', 'PSČ')
				->setRequired();
		}, $copies, $maxCopies);

		$multiplier->addCreateButton('Přidat adresu');

		$multiplier->addRemoveButton('Odebrat adresu');

		$form->addSubmit('save', 'Uložit profil');

		$form->onAnchor[] = function(Form $form) {
			$form->getPresenter()->redrawControl('profileEditForm');
		};

		$form->onSuccess[] = $this->formSucceeded(...);

		$this->onSuccess = $onSuccess;
		$this->onFailure = $onFailure;

		return $form;
	}

	private function formSucceeded(Form $form, ArrayHash $values): void
	{
		try {
			$user = $this->usersFacade->getUser($this->user->getId());
		} catch (Exception $e) {
			($this->onFailure)('Uživatel nebyl nalezen');
			return;
		}

		$changed = false;

		if($user->email != $values->email) {
			try {
				$this->usersFacade->getUserByEmail($values->email);
				($this->onFailure)('Uživatel s tímto e-mailem již existuje');
				return;
			} catch (Exception $e) {}
			$changed = true;
		}

		if($user->name != $values->name) {
			$changed = true;
		}

		$user->name = $values->name;
		$user->email = $values->email;

		try {
			$this->usersFacade->saveUser($user);
		} catch (Exception $e) {
			($this->onFailure)('Profil se nepodařilo uložit');
			return;
		}

		$notDeletedAddresses = [];

		//addresses
		foreach ($values->multiplier as $addressValue) {
			if($addressValue->addressId) {
				$address = $this->addressFacade->getAddress($addressValue->addressId);
			} else {
				$address = new Address();
				$address->user = $user;
			}

			$address->name = $addressValue->name;
			$address->street = $addressValue->street;
			$address->city = $addressValue->city;
			$address->zip = $addressValue->zip;

			try {
				$address = $this->addressFacade->saveAddress($address);
			} catch (Exception $e) {
				($this->onFailure)('Adresu se nepodařilo uložit');
				return;
			}

			$notDeletedAddresses[] = $address->id;
		}

		//delete old addresses
		$this->addressFacade->deleteAddresses($user->id, $notDeletedAddresses);

		if($changed) {
			($this->onSuccess)('Profil byl úspěšně uložen, pro pokračování se prosím znovu přihlaste', $changed);
		} else {
			($this->onSuccess)('Profil byl úspěšně uložen', $changed);
		}
	}
}