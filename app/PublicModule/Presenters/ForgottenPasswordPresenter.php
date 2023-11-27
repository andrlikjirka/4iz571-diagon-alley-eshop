<?php

namespace App\PublicModule\Presenters;


use App\PublicModule\Forms\ForgottenPasswordFormFactory;
use Nette\Forms\Form;

class ForgottenPasswordPresenter extends BasePresenter
{
    /** @persistent */
    public string $backlink = '';

    public function __construct(
        private readonly ForgottenPasswordFormFactory $forgottenPasswordFormFactory
    )
    {
        parent::__construct();
    }

    public function beforeRender(): void
    {
        $this->setLayout('loginRegistrationLayout');
    }

    public function actionDefault(): void
    {
        if ($this->user->isLoggedIn()) {
            $this->restoreRequest($this->backlink);
            $this->redirect(':Public:Homepage:default');
        }
    }

    protected function createComponentForgottenPasswordForm(): Form
    {
        $onSuccess = function (string $message): void {
            $this->flashMessage($message, 'info');
            $this->restoreRequest($this->backlink);
            $this->redirect(':Public:Homepage:default');
        };
        $onFailure = function (string $message): void {
            $this->flashMessage($message, 'danger');
            $this->redirect('this');
        };
        return $this->forgottenPasswordFormFactory->create($onSuccess, $onFailure);
    }


}