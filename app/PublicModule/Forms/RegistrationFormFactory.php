<?php

namespace App\PublicModule\Forms;

use App\Forms\FormFactory;
use App\Model\Authenticator\Authenticator;
use App\Model\Facades\RolesFacade;
use App\Model\Facades\UsersFacade;
use Closure;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use App\Model\Orm\Users\User;
use Nette\Utils\ArrayHash;
use Nette\Utils\Arrays;

/**
 * Class RegistrationFormFactory
 * @package App\PublicModule\Forms
 * @author Jiří Andrlík
 */
class RegistrationFormFactory
{

    private Closure $onSuccess;
    private Closure $onFailure;

    public function __construct(
        private readonly FormFactory          $formFactory,
        private readonly UsersFacade          $usersFacade,
        private readonly RolesFacade          $rolesFacade,
        private readonly Passwords            $passwords,
        private readonly \Nette\Security\User $user,
        private readonly Authenticator        $authenticator
    )
    {
    }

    public function create(callable $onSuccess, callable $onFailure): Form
    {
        $form = $this->formFactory->create();

        $form->addText('name', 'Jméno a příjmení')
            ->setRequired('Zadejte své jméno!')
            ->setHtmlAttribute('placeholder', 'Zadejte jméno a příjmení')
            ->setHtmlAttribute('autofocus')
            ->setHtmlAttribute('maxlength', 50)
            ->addRule(Form::MAX_LENGTH, 'Jméno je příliš dlouhé, může mít maximálně 50 znaků.', 50);

        $form->addEmail('email', 'E-mail')
            ->setRequired('Zadejte platný email!')
            ->setHtmlAttribute('placeholder', 'Zadejte email')
            ->addRule(function (TextInput $textInput) {
                try {
                    $this->usersFacade->getUserByEmail($textInput->value);
                } catch (\Exception $e) {
                    //pokud nebyl uživatel nalezen (tj. je vyhozena výjimka), je to z hlediska registrace v pořádku
                    return true;
                }
                return false;
            }, 'Uživatel s tímto e-mailem je již registrován.');

        $password = $form->addPassword('password', 'Heslo')
            ->setRequired('Zadejte požadované heslo!')
            ->setHtmlAttribute('placeholder', 'Zadejte heslo')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí obsahovat minimálně 5 znaků', 5);

        $form->addPassword('password2', 'Potvrzení hesla')
            ->setRequired('Potvrďte požadované heslo!')
            ->setHtmlAttribute('placeholder', 'Zadejte heslo znovu')
			->setOmitted()
            ->addRule(Form::EQUAL, 'Hesla se neshodují', $password);

        $form->addSubmit('register', 'Registrovat se');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }

    private function formSucceeded(Form $form, ArrayHash $values): void
    {
        $newUser = new User();

		Arrays::toObject($values, $newUser);

        $newUser->password = $this->passwords->hash($values->password);
        $newUser->role = $this->rolesFacade->getRoleByName('customer');

        try {
            $this->usersFacade->saveUser($newUser);
        } catch (\Exception $e) {
            ($this->onFailure)('Při registraci se vyskytla chyba.');
            return;
        }

        $this->user->setAuthenticator($this->authenticator);
        try {
            $this->user->login($values->email, $values->password);
        } catch (AuthenticationException $e) {
            ($this->onFailure)('Při registraci se vyskytla chyba.');
            return;
        }
        ($this->onSuccess)('Registrace uživatele proběhla úspěšně.');
    }

}