<?php

namespace App\PublicModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\ForgottenPasswordsFacade;
use App\Model\Facades\UsersFacade;
use App\Model\MailSender\MailSender;
use App\Model\Orm\ForgottenPasswords\ForgottenPassword;
use Closure;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\InvalidLinkException;
use Nette\Forms\Form;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Utils\ArrayHash;

class ForgottenPasswordFormFactory
{
    private Closure $onSuccess;

    private Closure $onFailure;


    public function __construct(
        private readonly FormFactory              $formFactory,
        private readonly UsersFacade              $usersFacade,
        private readonly LinkGenerator            $linkGenerator,
        private readonly ForgottenPasswordsFacade $forgottenPasswordsFacade,
        private readonly MailSender $mailService
    )
    {
    }

    public function create(callable $onSuccess, callable $onFailure): Form
    {
        $form = $this->formFactory->create();

        $form->addEmail('email', 'E-mail')
            ->setRequired('Zadejte platný e-mail!')
            ->setHtmlAttribute('placeholder', 'E-mail')
            ->setHtmlAttribute('autofocus');

        $form->addSubmit('submit', 'Poslat e-mail pro obnovu hesla');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }


    public function formSucceeded(Form $form, ArrayHash $values): void
    {

        try {
            $user = $this->usersFacade->getUserByEmail($values->email);
//            exit(var_dump($user->id));
        } catch (\Exception $e) {
            ($this->onSuccess)('Pokud uživatelský účet s daným e-mailem existuje, poslali jsme vám odkaz na změnu hesla.');
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

        $this->mailService->sendForgottenPasswordMail($user, $mailLink);

        ($this->onSuccess)('Pokud uživatelský účet s daným e-mailem existuje, poslali jsme vám odkaz na změnu hesla.');
    }

}