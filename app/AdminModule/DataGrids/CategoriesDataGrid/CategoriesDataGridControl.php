<?php

namespace App\AdminModule\DataGrids\CategoriesDataGrid;

use App\Model\Facades\CategoriesFacade;
use App\Model\Orm\Categories\Category;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use stdClass;
use Ublaboo\DataGrid\Column\Action\Confirmation\CallbackConfirmation;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class CategoriesDataGridControl
 * @package App\AdminModule\DataGrids\CategoriesDataGrid
 * @author Jiří Andrlík
 * @property-read Template|stdClass $template
 */
class CategoriesDataGridControl extends Control
{
    public function __construct(
        private readonly CategoriesFacade $categoriesFacade
    )
    {
    }

    public function createComponentDataGrid(): DataGrid
    {
        $grid = new DataGrid();
        $grid->setDataSource($this->categoriesFacade->findAllCategories());

        $grid->addColumnNumber('id', 'ID kategorie')
            ->setAlign('left')
            ->setSortable();

        $grid->addColumnText('name', 'Název kategorie')
            ->setAlign('left')
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('parent', 'Nadřazená kategorie')
            ->setAlign('left')
            ->setSortable()
            ->setRenderer(function (Category $category): ?string {
                return !empty($category->parent) ? $category->parent->name : null;
            });

        $grid->addAction('showed', '')
            ->setIcon(function (Category $category): string {
                return $category->showed ? 'eye' : 'eye-slash';
            })
            ->setClass('ajax btn btn-xs btn-secondary ms-1 me-1 mb-1');

        $grid->addActionCallback('edit', '')
            ->setIcon('pen-to-square')
            ->setClass('btn btn-xs btn-warning ms-1 me-1 mb-1')
            ->onClick[] = function ($itemId): void {
            $this->presenter->redirect('Categories:edit', ['id' => $itemId]);
        };

        $grid->addAction('delete', '')
            ->setIcon('trash')
            ->setClass('ajax btn btn-xs btn-danger ms-1 me-1 mb-1')
            ->setConfirmation(
                new CallbackConfirmation(
                    function($category) {
                        return 'Opravdu chcete odstranit kategorii '.$category->name.'?';
                    }
                )
            );

        $grid->setItemsPerPageList([1, 10, 20, 50, 100, 200, 500], false)
            ->setDefaultPerPage(10);

        return $grid;
    }

    public function handleShowed(int $id): void
    {
        $category = $this->categoriesFacade->getCategoryById($id);
        $category->showed = !$category->showed;
        try {
            $this->categoriesFacade->saveCategory($category);
        } catch (\Exception $e) {
            $this->presenter->flashMessage($e->getMessage(), 'danger');
        }
        if($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flashes');
            $this['dataGrid']->redrawItem($category->id);
        } else {
            $this->presenter->redirect('this');
        }
    }

    public function handleDelete(int $id): void
    {
        $category = $this->categoriesFacade->getCategoryById($id);
        if (count($category->products) == 0) {
            //pokud kategorie neobsahuje produkty, zkontrolujeme jestli neobsahuje produkty některá z podřízených kategorií
            foreach ($category->childrenRecursively as $child) {
                if (count($child->products) != 0) {
                    $this->presenter->flashMessage('Nelze odstranit kategorii, ve které jsou přiřazeny produkty.', 'danger');
                    if($this->presenter->isAjax()) {
                        $this->presenter->redrawControl('flashes');
                        $this['dataGrid']->redrawItem($category->id);
                    } else {
                        $this->presenter->redirect('this');
                    }
                }
            }

            $category->deleted = true;
            try {
                $this->categoriesFacade->saveCategory($category);
            } catch (\Exception $e) {
                $this->presenter->flashMessage($e->getMessage());
                if($this->presenter->isAjax()) {
                    $this->presenter->redrawControl('flashes');
                } else {
                    $this->presenter->redirect('this');
                }
            }
            $this->presenter->flashMessage('Kategorie byla úspěšně odstraněna', 'success');
            if($this->presenter->isAjax()) {
                $this->presenter->redrawControl('flashes');
                $this['dataGrid']->reload();
            } else {
                $this->presenter->redirect('this');
            }
        } else {
            $this->presenter->flashMessage('Nelze odstranit kategorii, ve které jsou přiřazeny produkty.', 'danger');
            if($this->presenter->isAjax()) {
                $this->presenter->redrawControl('flashes');
            } else {
                $this->presenter->redirect('this');
            }
        }
    }

    public function render(): void
    {
        $this->template->render(__DIR__.'/templates/categoriesDataGrid.latte');
    }

}