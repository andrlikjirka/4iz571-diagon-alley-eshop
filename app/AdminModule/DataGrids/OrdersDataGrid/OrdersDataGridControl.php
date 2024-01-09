<?php

namespace App\AdminModule\DataGrids\OrdersDataGrid;

use App\Model\Facades\OrdersFacade;
use App\Model\Orm\Orders\Order;
use App\Model\Orm\OrderStatus\OrderStatus;
use Nette\Application\UI\Control;
use Ublaboo\DataGrid\Column\Action\Confirmation\CallbackConfirmation;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class OrdersDataGridControl
 * @package App\AdminModule\DataGrids\OrdersDataGrid
 * @author Jiří Andrlík
 */
class OrdersDataGridControl extends Control
{
    public function __construct(
        private readonly OrdersFacade $ordersFacade,
    )
    {
    }

    public function createComponentDataGrid(): DataGrid
    {
        $grid = new DataGrid();

        $grid->setTemplateFile(__DIR__ . '/templates/datagridTemplate.latte');
        $grid->setDataSource($this->ordersFacade->findAllOrders());

        $grid->addColumnNumber('id', 'ID objednávky')
            ->setAlign('left')
            ->setSortable()
            ->setRenderer(function (Order $order): int {
                return $order->id;
            });

        $grid->addColumnDateTime('created', 'Datum vytvoření')
            ->setAlign('left')
            ->setSortable();

        $grid->addColumnText('orderStatus', 'Stav objednávky')
            ->setAlign('left')
            ->setSortable()
            ->setRenderer(function (Order $order): string {
                return $order->orderStatus->name;
            });

        $grid->addActionCallback('show', '')
            ->setIcon('pen-to-square')
            ->setClass('btn btn-xs btn-warning ms-1 me-3 mb-1')
            ->onClick[] = function ($orderId): void {
            $this->presenter->redirect(':Admin:Orders:show', ['id' => $orderId]);
        };

        $grid->addAction('receive', 'Přijatá', 'receive!')
            ->setClass(function ($order) {
                return $order->orderStatus->id != OrderStatus::RECEIVED ? 'ajax btn btn-xs btn-light' : 'ajax btn btn-xs btn-light disabled';
            })
            ->setConfirmation(
                new CallbackConfirmation(
                    function($order) {
                        return 'Opravdu chcete přepnout objednávku '.$order->id.' do stavu Přijatá?';
                    }
                )
            );

        $grid->addAction('inProgress', 'Zpracovává se')
            ->setClass(function ($order) {
                return $order->orderStatus->id != OrderStatus::IN_PROGRESS ? 'ajax btn btn-xs btn-secondary' : 'ajax btn btn-xs btn-secondary disabled';
            })
            ->setConfirmation(
                new CallbackConfirmation(
                    function($order) {
                        return 'Opravdu chcete přepnout objednávku '.$order->id.' do stavu Zpracovává se?';
                    }
                )
            );

        $grid->addAction('settle', 'Vyřízená')
            ->setClass(function ($order) {
                return $order->orderStatus->id != OrderStatus::SETTLED ? 'ajax btn btn-xs btn-success' : 'ajax btn btn-xs btn-success disabled';
            })
            ->setConfirmation(
                new CallbackConfirmation(
                    function($order) {
                        return 'Opravdu chcete přepnout objednávku '.$order->id.' do stavu Vyřízená?';
                    }
                )
            );

        $grid->addAction('cancel', 'Stornovaná')
            ->setClass(function ($order) {
                return $order->orderStatus->id != OrderStatus::CANCELLED ? 'ajax btn btn-xs btn-danger' : 'ajax btn btn-xs btn-danger disabled';
            })
            ->setConfirmation(
                new CallbackConfirmation(
                    function($order) {
                        return 'Opravdu chcete přepnout objednávku '.$order->id.' do stavu Stornovaná?';
                    }
                )
            );

        $grid->setItemsPerPageList([1, 10, 20, 50, 100, 200, 500], false)
            ->setDefaultPerPage(10);

        $grid->setDefaultSort(['created' => 'ASC']);

        return $grid;
    }

    public function handleReceive(int $id): void
    {
        try {
            $order = $this->ordersFacade->changeOrderStatus($id, OrderStatus::RECEIVED);
        } catch (\Exception $e) {
            $this->presenter->flashMessage($e->getMessage(), 'danger');
        }
        $this->presenter->flashMessage('Stav objednávky '.$id.' byl změněn.', 'success');
        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flashes');
            isset($order) ? $this['dataGrid']->redrawItem($order->id) : $this['dataGrid']->reload();
        } else {
            $this->presenter->redirect('this');
        }
    }

    public function handleInProgress(int $id): void
    {
        try {
            $order = $this->ordersFacade->changeOrderStatus($id, OrderStatus::IN_PROGRESS);
        } catch (\Exception $e) {
            $this->presenter->flashMessage($e->getMessage(), 'danger');
        }
        $this->presenter->flashMessage('Stav objednávky '.$id.' byl změněn.', 'success');
        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flashes');
            isset($order) ? $this['dataGrid']->redrawItem($order->id) : $this['dataGrid']->reload();
        } else {
            $this->presenter->redirect('this');
        }
    }

    public function handleSettle(int $id): void
    {
        try {
            $order = $this->ordersFacade->changeOrderStatus($id, OrderStatus::SETTLED);
        } catch (\Exception $e) {
            $this->presenter->flashMessage($e->getMessage(), 'danger');
        }
        $this->presenter->flashMessage('Stav objednávky '.$id.' byl změněn.', 'success');
        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flashes');
            isset($order) ? $this['dataGrid']->redrawItem($order->id) : $this['dataGrid']->reload();
        } else {
            $this->presenter->redirect('this');
        }
    }

    public function handleCancel(int $id): void
    {
        try {
            $order = $this->ordersFacade->changeOrderStatus($id, OrderStatus::CANCELLED);
        } catch (\Exception $e) {
            $this->presenter->flashMessage($e->getMessage(), 'danger');
        }
        $this->presenter->flashMessage('Stav objednávky '.$id.' byl změněn.', 'success');
        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flashes');
            isset($order) ? $this['dataGrid']->redrawItem($order->id) : $this['dataGrid']->reload();
        } else {
            $this->presenter->redirect('this');
        }
    }

    public function render(): void
    {
        $this->template->render(__DIR__ . '/templates/ordersDataGrid.latte');
    }
}