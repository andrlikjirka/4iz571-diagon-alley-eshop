<?php

namespace App\AdminModule\DataGrids\CategoriesDataGrid;

use App\Model\Facades\CategoriesFacade;
use App\Model\Orm\Categories\Category;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Tokenizer\Exception;
use stdClass;
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

        $grid->addColumnStatus('showed', 'Zobrazení')
            ->setAlign('left')
            ->addOption(0, 'Ne')
            ->setClass('badge rounded-pill text-bg-light')
            ->endOption()
            ->addOption(1, 'Ano')
            ->setClass('badge rounded-pill text-bg-dark')
            ->endOption()
            ->setSortable();

        $grid->addActionCallback('edit', '')
            ->setIcon('pen-to-square')
            ->setClass('btn btn-xs btn-warning ms-1 me-1 mb-1')
            ->onClick[] = function ($itemId): void {
            $this->presenter->redirect('Categories:edit', ['id' => $itemId]);
        };

        $grid->addAction('delete', '')
            ->setIcon('trash')
            ->setClass('btn btn-xs btn-danger ms-1 me-1 mb-1');

        $grid->setItemsPerPageList([10, 20], false);

        return $grid;
    }

    public function handleDelete(int $id): void
    {
        $category = $this->categoriesFacade->getCategoryById($id);
        if (count($category->products) == 0) {
            //pokud kategorie neobsahuje produkty, zkontrolujeme jestli neobsahuje produkty některá z podřízených kategorií
            foreach ($category->childrenRecursively as $child) {
                if (count($child->products) != 0) {
                    $this->presenter->flashMessage('Nelze odstranit kategorii, ve které jsou přiřazeny produkty.', 'danger');
                    $this->redirect('this');
                }
            }

            $category->deleted = true;
            try {
                $this->categoriesFacade->saveCategory($category);
                $this->presenter->flashMessage('Kategorie byla úspěšně odstraněna', 'success');
            } catch (\Exception $e) {
                $this->presenter->flashMessage($e->getMessage());
                $this->redirect('this');
            }
        } else {
            $this->presenter->flashMessage('Nelze odstranit kategorii, ve které jsou přiřazeny produkty.', 'danger');
            $this->redirect('this');
        }
    }

    public function render(): void
    {
        $this->template->render(__DIR__.'/templates/categoriesDataGrid.latte');
    }

}