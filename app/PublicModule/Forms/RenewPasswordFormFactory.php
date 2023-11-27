<?php

namespace App\PublicModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\UsersFacade;
use Closure;
use Nette\Forms\Form;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;
use function Symfony\Component\String\u;

class RenewPasswordFormFactory
{

    private Closure $onSuccess;

    private Closure $onFailure;

    public function __construct(
        private readonly FormFactory $formFactory,
        private readonly UsersFacade $usersFacade,
        private readonly Passwords $passwords
    ){}

    public function create(callable $onSuccess, callable $onFailure): Form
    {
        $form = $this->formFactory->create();

        $form->addHidden('userId');

        $password = $form->addPassword('password', 'Heslo:')
            ->setRequired('Zadejte požadované heslo!')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí obsahovat minimálně 5 znaků', 5);
        $form->addPassword('password2', 'Potvrzení hesla:')
            ->setRequired('Potvrďte požadované heslo!')
            ->addRule(Form::EQUAL, 'Hesla se neshodují', $password);

        $form->addSubmit('submit', 'Uložit nové heslo');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }

    public function formSucceeded(Form $form, ArrayHash $values): void
    {
        try {
            $user = $this->usersFacade->getUser($values->userId);
        } catch (\Exception $e) {
            ($this->onFailure)('Zvolený uživatelský účet nebyl nalezen.');
            return;
        }

        $user->password = $this->passwords->hash($values->password);
        try {
            $this->usersFacade->saveUser($user);
        } catch (\Exception $e) {
            ($this->onFailure)('Při registraci se vyskytla chyba.');
            return;
        }
        ($this->onSuccess)('Heslo bylo změněno.');
    }

}