<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;


use App\PublicModule\Forms\RegistrationFormFactory;
use Nette\Application\AbortException;
use Nette\Forms\Form;

/**
 * Class RegistrationPresenter
 * @package App\PublicModule\Presenters
 * @author Jiří Andrlík
 * @property string $backlink
 */
class RegistrationPresenter extends BasePresenter
{
    /** @persistent */
    public string $backlink = '';

    /**
     * RegistrationPresenter constructor
     * @param RegistrationFormFactory $registrationFormFactory
     */
    public function __construct(
        private readonly RegistrationFormFactory $registrationFormFactory
    )
    {
        parent::__construct();
    }

    public function beforeRender(): void
    {
        $this->setLayout('loginRegistrationLayout');
    }

    /**
     * Akce pro registraci - pokud už je uživatel přihlášen/registrován, tak ho přesměrujeme na homepage
     * @return void
     * @throws AbortException
     */
    public function actionDefault(): void
    {
        if ($this->user->isLoggedIn()) {
            $this->restoreRequest($this->backlink);
            $this->redirect('Homepage:default');
        }
    }

    /**
     * Formulář pro registraci nového uživatele
     * @return Form
     */
    public function createComponentRegistrationForm(): Form
    {
        $onSuccess = function (string $message): void {
            $this->flashMessage($message, 'success');
            $this->redirect('Homepage:default');
        };

        $onFailure = function (string $message): void {
            $this->flashMessage($message, 'danger');
            $this->redirect('this');
        };
        return $this->registrationFormFactory->create($onSuccess, $onFailure);
    }

}