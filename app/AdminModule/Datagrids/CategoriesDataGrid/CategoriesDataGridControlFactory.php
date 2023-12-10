<?php

namespace App\AdminModule\Datagrids\CategoriesDataGrid;

/**
 * Interface CategoriesDataGridControlFactory
 * @package App\AdminModule\Datagrids\CategoriesDataGrid
 * @author Jiří Andrlík
 */
interface CategoriesDataGridControlFactory
{
    public function create(): CategoriesDataGridControl;
}