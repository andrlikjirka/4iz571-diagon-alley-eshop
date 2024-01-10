<?php

namespace App\AdminModule\DataGrids\UsersDataGrid;

use App\Model\Facades\UsersFacade;
use App\Model\Orm\Users\User;
use Nette\Application\UI\Control;
use Nette\Tokenizer\Exception;
use Ublaboo\DataGrid\Column\Action\Confirmation\CallbackConfirmation;
use Ublaboo\DataGrid\DataGrid;
use function Symfony\Component\String\u;

/**
 * Class UsersDataGridControl
 * @package App\AdminModule\DataGrids\UsersDataGrid
 * @author Jiří Andrlík
 */
class UsersDataGridControl extends Control
{
    public function __construct(
        private readonly UsersFacade $usersFacade
    )
    {
    }

    public function createComponentDataGrid(): DataGrid
    {
        $grid = new DataGrid();
        $grid->setTemplateFile(__DIR__ . '/templates/dataGridTemplate.latte');
        $grid->setDataSource($this->usersFacade->findAllUsers());

        $grid->addColumnNumber('id', 'ID uživatele')
            ->setAlign('left')
            ->setSortable();

        $grid->addColumnText('name', 'Jméno uživatele')
            ->setAlign('left')
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('email', 'Email')
            ->setAlign('left')
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('role', 'Role')
            ->setAlign('left')
            ->setRenderer(function ($user): string {
                return $user->role->name;
            })
            ->setSortable()
            ->setFilterSelect([
                '' => 'Všechny',
                'admin' => 'admin',
                'customer' => 'customer',
                'editor' => 'editor',
                'storeman' => 'storeman',
            ], 'role->name');

        $grid->addColumnText('facebokId', 'Spojeno s FB účtem')
            ->setAlign('left')
            ->setSortable()
            ->setRenderer(function (User $user): string {
                return $user->facebookId ? 'Ano' : 'Ne';
            });

        $grid->addActionCallback('edit', '')
            ->setIcon('pen-to-square')
            ->setClass('btn btn-xs btn-warning ms-1 me-1 mb-1')
            ->onClick[] = function ($itemId): void {
            $this->presenter->redirect('Users:edit', ['id' => $itemId]);
        };

        $grid->addAction('blocked', '')
            ->setIcon(function (User $user): string {
                return $user->blocked ? 'eye-slash' : 'eye';
            })
            ->setClass('ajax btn btn-xs btn-secondary ms-1 me-1 mb-1');

        $grid->addAction('delete', '')
            ->setIcon('trash')
            ->setClass('ajax btn btn-xs btn-danger ms-1 me-1 mb-1')
            ->setConfirmation(new CallbackConfirmation(
                function ($user) {
                    return 'Opravdu chcete smazat uživatele ' . $user->name . ' (ID = ' . $user->id . ')?';
                }
            ));

        return $grid;
    }

    public function handleBlocked(int $id): void
    {
        $user = $this->usersFacade->getUser($id);
        $user->blocked = !$user->blocked;
        try {
            $this->usersFacade->saveUser($user);
        } catch (\Exception $e) {
            $this->presenter->flashMessage($e->getMessage(), 'danger');
        }

        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flashes');
            $this['dataGrid']->redrawItem($user->id);
        } else {
            $this->presenter->redirect('this');
        }
    }

    public function handleDelete(int $id): void
    {
        $user = $this->usersFacade->getUser($id);
        $user->deleted = true;
        try {
            $this->usersFacade->saveUser($user);
            $this->presenter->flashMessage('Uživatel '. $user->name . ' (ID = '. $user->id . ') byl úspěšně smazán.', 'success');
        } catch (\Exception $e) {
            $this->presenter->flashMessage($e->getMessage(), 'danger');
        }

        if($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flashes');
            $this['dataGrid']->redrawControl();
        } else {
            $this->presenter->redirect('this');
        }
    }

    public function render(): void
    {
        $this->template->render(__DIR__ . '/templates/usersDataGrid.latte');
    }
}