<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\DataGrids\OrdersDataGrid\OrdersDataGridControl;
use App\AdminModule\DataGrids\OrdersDataGrid\OrdersDataGridControlFactory;
use App\AdminModule\Forms\OrderStatusEditFormFactory;
use App\Model\Facades\OrdersFacade;
use Nette\Forms\Form;

/**
 * Class OrdersPresenter
 * @package App\AdminModule\Presenters
 * @author Jiří Andrlík
 */
class OrdersPresenter extends BasePresenter
{
    public function __construct(
        private readonly OrdersFacade $ordersFacade,
        private readonly OrdersDataGridControlFactory $ordersDataGridControlFactory,
        private readonly OrderStatusEditFormFactory $orderStatusEditFormFactory
    )
    {
        parent::__construct();
    }

    /**
     * Akce vykreslující seznam objednávek
     * @return void
     */
    public function renderDefault()
    {

    }

    /**
     * Akce vykreslující detail objednávky
     * @param int $id
     * @return void
     */
    public function renderShow(int $id): void
    {
        $order = $this->ordersFacade->getOrderById($id);
        $this->presenter->getComponent('orderStatusEditForm')->setDefaults([
            'orderId' => $order->id,
            'orderStatus' => $order->orderStatus->id
        ]);
        $this->template->order = $order;
    }

    public function createComponentOrderStatusEditForm(): Form
    {
        $onSuccess = function (string $message) {
            $this->flashMessage($message, 'success');
            if ($this->isAjax()) {
                $this->redrawControl('flashes');
                $this->redrawControl('content');
            } else {
                $this->redirect('this');
            }
        };
        $onFailure = function (string $message) {
            $this->flashMessage($message, 'danger');
            if ($this->isAjax()) {
                $this->redrawControl('flashes');
            } else {
                $this->redirect('this');
            }
        };
        return $this->orderStatusEditFormFactory->create($onSuccess, $onFailure);
    }

    protected function createComponentOrdersDataGrid(): OrdersDataGridControl
    {
        return $this->ordersDataGridControlFactory->create();
    }
}