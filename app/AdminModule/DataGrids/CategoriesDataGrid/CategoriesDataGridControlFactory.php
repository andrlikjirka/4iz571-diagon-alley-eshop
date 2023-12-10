<?php

namespace App\AdminModule\DataGrids\CategoriesDataGrid;

/**
 * Interface CategoriesDataGridControlFactory
 * @package App\AdminModule\DataGrids\CategoriesDataGrid
 * @author Jiří Andrlík
 */
interface CategoriesDataGridControlFactory
{
    public function create(): CategoriesDataGridControl;
}