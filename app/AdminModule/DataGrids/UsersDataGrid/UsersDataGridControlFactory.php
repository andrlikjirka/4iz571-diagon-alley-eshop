<?php

namespace App\AdminModule\DataGrids\UsersDataGrid;

/**
 * Interface UsersDataGridControlFactory
 * @package App\AdminModule\DataGrids\UsersDataGrid
 * @author Jiří Andrlík
 */
interface UsersDataGridControlFactory
{
    public function create(): UsersDataGridControl;
}