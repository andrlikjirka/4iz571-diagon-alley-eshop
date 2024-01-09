<?php

namespace App\PublicModule\DataGrids\MyOrdersDataGrid;

use App\Model\Facades\OrdersFacade;
use App\Model\Orm\Orders\Order;
use mysql_xdevapi\DatabaseObject;
use Nette\Application\UI\Control;
use Nette\Security\User;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class MyOrdersDataGridControl
 * @package App\PublicModule\DataGrids\MyOrdersDataGrid
 * @author Jiří Andrlík
 */
class MyOrdersDataGridControl extends Control
{
    public function __construct(
        private readonly User $user,
        private readonly OrdersFacade $ordersFacade
    )
    {}

    public function createComponentDataGrid(): DataGrid
    {
        $grid = new DataGrid();
        $grid->setTemplateFile(__DIR__.'/templates/datagrid_template.latte');
        $grid->setDataSource($this->ordersFacade->findOrdersByUserId($this->user->id));

        $grid->addColumnNumber('id', 'ID objednávky')
            ->setAlign('left')
            ->setSortable()
            ->setRenderer(function (Order $order): int {
                return $order->id;
            });

        $grid->addColumnDateTime('created', 'Datum vytvoření')
            ->setAlign('left')
            ->setSortable();

        $grid->addColumnText('price', 'Cena objednávky')
            ->setAlign('left')
            ->setRenderer(function (Order $order): string {
                return $order->galleonTotalPrice.'G '.$order->sickleTotalPrice.'S '.$order->knutTotalPrice.'K';
            });

        $grid->addColumnText('orderStatus', 'Stav objednávky')
            ->setAlign('left')
            ->setRenderer(function (Order $order): string {
                return $order->orderStatus->name;
            });

        $grid->addActionCallback('show', 'Zobrazit detail')
                ->setClass('btn btn-sm btn-light ms-1 me-1')
            ->onClick[] = function ($orderId): void {
                $this->presenter->redirect(':Public:Orders:showMyOrder', ['id' => $orderId]);
            };

        $grid->setItemsPerPageList([1, 10, 20, 50, 100, 200, 500], false)
            ->setDefaultPerPage(10);

        return $grid;
    }


    public function render(): void
    {
        $this->template->render(__DIR__.'/templates/myOrdersDataGrid.latte');
    }

}