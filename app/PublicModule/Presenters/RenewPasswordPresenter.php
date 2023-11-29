<?php

namespace App\PublicModule\Presenters;

use App\Model\Facades\ForgottenPasswordsFacade;
use App\Model\Facades\UsersFacade;
use App\PublicModule\Forms\RenewPasswordFormFactory;
use Nette\Application\BadRequestException;
use Nette\Forms\Form;
use Nette\Mail\Mailer;

class RenewPasswordPresenter extends BasePresenter
{
    /** @persistent */
    public string $backlink = '';

    public function __construct(
        private readonly RenewPasswordFormFactory $renewPasswordFormFactory,
        private readonly ForgottenPasswordsFacade $forgottenPasswordFacade,
        private readonly UsersFacade              $usersFacade
    )
    {
        parent::__construct();
    }

    public function beforeRender()
    {
        $this->setLayout('loginRegistrationLayout');
    }

    public function actionDefault(int $userId, string $code): void
    {
        if ($this->forgottenPasswordFacade->isValidForgottenPassword($userId, $code)) {
            #region odkaz na obnovu hesla byl platný
            try {
                $userEntity = $this->usersFacade->getUser($userId);
            } catch (\Exception $e) {
                throw new BadRequestException('Požadovaný uživatel neexistuje.','error');
            }

            $form = $this->getComponent('renewPasswordForm');
            $form->setDefaults(['userId' => $userEntity->id]);
            #endregion odkaz na obnovu hesla byl platný
        } else {
            #region odkaz již není platný
            $this->flashMessage('Odkaz na změnu hesla již není platný. Pokud potřebujete heslo obnovit, zašlete žádost znovu.','danger');
            $this->redirect(':Public:Homepage:default');
            #endregion odkaz již není platný
        }
    }

    protected function createComponentRenewPasswordForm(): Form
    {
        $onSuccess = function (string $message): void {
            $this->flashMessage($message, 'success');
            $this->restoreRequest($this->backlink);
            $this->redirect(':Public:Homepage:default');
        };
        $onFailed = function (string $message): void {
            $this->flashMessage($message, 'danger');
            $this->redirect('this');
        };
        return $this->renewPasswordFormFactory->create($onSuccess, $onFailed);
    }

}