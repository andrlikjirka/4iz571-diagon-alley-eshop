<?php

namespace App\AdminModule\Datagrids;

use App\Model\Facades\CategoriesFacade;
use App\Model\Orm\Categories\Category;
use Nette\Application\UI\Presenter;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class CategoriesDataGridFactory
 * @package App\AdminModule\Datagrids
 * @author Jiří Andrlík
 */
class CategoriesDataGridFactory
{
    public function __construct(
        private readonly CategoriesFacade $categoriesFacade
    )
    {
    }

    public function create(Presenter $presenter): DataGrid
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
            ->setRenderer(function (Category $category): ?int {
                return !empty($category->parent) ? $category->parent->id : null;
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
            ->onClick[] = function ($itemId) use ($presenter): void {
            $presenter->redirect('Categories:edit', ['id' => $itemId]);
        };

        $grid->addActionCallback('delete', '')
            ->setIcon('trash')
            ->setClass('btn btn-xs btn-danger ms-1 me-1 mb-1')
            ->onClick[] = function ($itemId) use ($presenter): void {
            $presenter->redirect('Categories:delete', ['id' => $itemId]);
        };

        $grid->setItemsPerPageList([10, 20], false);

        return $grid;
    }
}