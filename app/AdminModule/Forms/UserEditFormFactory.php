<?php

namespace App\AdminModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\ForgottenPasswordsFacade;
use App\Model\Facades\UsersFacade;
use App\Model\MailSender\MailSender;
use App\Model\Orm\Users\User;
use Closure;
use Exception;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\InvalidLinkException;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Mail\Mailer;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;
use Nette\Utils\Random;
use function Symfony\Component\String\u;

/**
 * Class UserEditFormFactory
 * @package App\AdminModule\Forms
 * @author Jiří Andrlík
 */
class UserEditFormFactory
{

    private Closure $onSuccess;
    private Closure $onFailure;

    public function __construct(
        private readonly FormFactory $formFactory,
        private readonly UsersFacade $usersFacade,
        private readonly ForgottenPasswordsFacade $forgottenPasswordsFacade,
        private readonly Passwords $passwords,
        private readonly LinkGenerator $linkGenerator,
        private readonly MailSender $mailService
    )
    {
    }

    public function create(callable $onSuccess, callable $onFailure): Form
    {
        $form = $this->formFactory->create();

        $form->addHidden('userId')
            ->setNullable();

        $form->addText('name', 'Jméno uživatele', maxLength: 255)
            ->setRequired()
            ->setHtmlAttribute('placeholder', 'Zadejte jméno uživatele')
            ->setHtmlAttribute('autofocus');

        $form->addEmail('email', 'Email uživatele')
            ->setRequired()
            ->setHtmlAttribute('placeholder', 'Zadejte email uživatele')
            ->addRule(function (TextInput $textInput) use ($form) {
                $userId = $form['userId']->getValue();
                try {
                    $user = $this->usersFacade->getUserByEmail($textInput->value);
                } catch (Exception $e) {
                    //pokud nebyl uživatel nalezen (tj. je vyhozena výjimka), je to z hlediska registrace v pořádku
                    return true;
                }
                if ($userId!== null and $this->usersFacade->getUser(intval($userId))->email == $user->email) {
                    return true;
                }
                return false;
            }, 'Uživatel s tímto e-mailem je již registrován.');

        $roles = $this->usersFacade->findRolesPairs();
        $form->addSelect('role', 'Role uživatele', $roles)
            ->setPrompt('Zvolte roli uživatele')
            ->setRequired();

        $form->addSubmit('save', 'Uložit');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }

    public function formSucceeded(Form $form, ArrayHash $values): void
    {
        if ($values['userId']) {
            $user = $this->usersFacade->getUser($values['userId']);
        } else {
            $user = new User();
        }
        unset($values['userId']);

        $user->name = $values['name'];
        $user->email = $values['email'];
        $user->role = $this->usersFacade->getRoleById($values['role']);
        $user->password = $this->passwords->hash(Random::generate(10));

        try {
            $this->usersFacade->saveUser($user);
        } catch (\Exception $e) {
            ($this->onFailure)($e->getMessage());
            return;
        }

        //vygenerování odkazu na změnu hesla
        $forgottenPassword = $this->forgottenPasswordsFacade->createNewForgottenPasswordCode($user);
        try {
            $this->forgottenPasswordsFacade->saveNewForgottenPasswordCode($forgottenPassword);
        } catch (\Exception $e) {
            ($this->onFailure)($e->getMessage());
            return;
        }
        try {
            $mailLink = $this->linkGenerator->link('Public:RenewPassword:default', ['userId' => $user->id, 'code' => $forgottenPassword->code]);
        } catch (InvalidLinkException $e) {
            ($this->onFailure)($e->getMessage());
            return;
        }

        //odeslání mailu
        $this->mailService->sendNewUserMail($user, $mailLink);

        ($this->onSuccess)('Nový uživatel s rolí '.$user->role->name.' byl vytvořen.');
    }

}