<?php

namespace App\PublicModule\DataGrids\MyOrdersDataGrid;

/**
 * Interface MyOrdersDataGridControlFactory
 * @package App\PublicModule\DataGrids\MyOrdersDataGrid
 * @author Jiří Andrlík
 */
interface MyOrdersDataGridControlFactory
{
    public function create(): MyOrdersDataGridControl;

}