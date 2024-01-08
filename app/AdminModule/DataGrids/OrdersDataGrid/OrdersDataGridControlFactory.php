<?php

namespace App\AdminModule\DataGrids\OrdersDataGrid;

/**
 * Interface OrdersDataGridControlFactory
 * @package App\AdminModule\DataGrids\OrdersDataGrid
 * @author Jiří Andrlík
 */
interface OrdersDataGridControlFactory
{
    public function create(): OrdersDataGridControl;
}