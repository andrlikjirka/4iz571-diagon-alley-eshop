<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\DataGrids\UsersDataGrid\UsersDataGridControl;
use App\AdminModule\DataGrids\UsersDataGrid\UsersDataGridControlFactory;
use App\AdminModule\Forms\UserEditFormFactory;
use App\Model\Facades\UsersFacade;
use Nette\Forms\Form;
use Nette\Tokenizer\Exception;

/**
 * Class UsersPresenter
 * @package App\AdminModule\Presenters
 * @author Jiří Andrlík
 */
class UsersPresenter extends BasePresenter
{
    public function __construct(
        private readonly UsersDataGridControlFactory $usersDataGridControlFactory,
        private readonly UserEditFormFactory $userEditFormFactory,
        private readonly UsersFacade $usersFacade
    )
    {
        parent::__construct();
    }

    public function renderDefault()
    {
    }

    public function renderAdd()
    {
    }

    public function renderEdit(int $id)
    {
        try {
            $user = $this->usersFacade->getUser($id);
        } catch (Exception $e) {
            $this->flashMessage('Požadovaný uživatel nebyl nalezen.', 'dange');
            $this->redirect('Users:default');
        }
        $form = $this->getComponent('userEditForm');
        $form->setDefaults([
            'userId' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->id
        ]);
        $this->template->user = $user;
    }

    public function createComponentUserEditForm(): Form
    {
        $onSuccess = function (string $message) {
            $this->flashMessage($message, 'success');
            $this->redirect('Users:default');
        };

        $onFailure = function (string $message) {
            $this->flashMessage($message, 'danger');
            $this->redirect('this');
        };

        return $this->userEditFormFactory->create($onSuccess, $onFailure);
    }

    protected function createComponentUsersDataGrid(): UsersDataGridControl
    {
        return $this->usersDataGridControlFactory->create();
    }

}