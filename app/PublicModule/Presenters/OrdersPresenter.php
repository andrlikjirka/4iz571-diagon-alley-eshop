<?php

namespace App\PublicModule\Presenters;

use App\Model\Facades\CartFacade;
use App\Model\Facades\OrdersFacade;
use App\Model\InvoiceGenerator\InvoiceGenerator;
use App\PublicModule\DataGrids\MyOrdersDataGrid\MyOrdersDataGridControl;
use App\PublicModule\DataGrids\MyOrdersDataGrid\MyOrdersDataGridControlFactory;
use App\PublicModule\Forms\CreateOrderFormFactory;
use Nette\Application\AbortException;
use Nette\Forms\Form;
use Nette\Http\Session;
use Nette\Http\SessionSection;

/**
 * Class OrdersPresenter
 * @package App\PublicModule\Presenters
 * @author Jiří Andrlík
 */
class OrdersPresenter extends BasePresenter
{
    private SessionSection $cartSession;

    public function __construct(
        private readonly CreateOrderFormFactory $createOrderFormFactory,
        private readonly CartFacade             $cartFacade,
        private readonly OrdersFacade $ordersFacade,
        Session                                 $session,
        private readonly MyOrdersDataGridControlFactory $myOrdersDataGridControlFactory,
        private readonly InvoiceGenerator $invoiceGenerator

    )
    {
        $this->cartSession = $session->getSection('cart');
        parent::__construct();
    }

    public function renderMyOrders(): void
    {
    }

    public function renderShowMyOrder(int $id): void
    {
        try {
            $order = $this->ordersFacade->getOrderById($id);
        } catch (\Exception $e) {
            $this->flashMessage('Objednávka nebyla nalezena.', 'danger');
            $this->redirect(':Public:Orders:myOrders');
        }
        $this->template->order = $order;
    }

    public function actionOrderInvoice(int $orderId): void
    {
        try {
            $order = $this->ordersFacade->getOrderById($orderId);
        } catch (\Exception $e) {
            $this->flashMessage('Objednávka nebyla nalezena.', 'danger');
            $this->redirect(':Public:Orders:myOrders');
        }
        if (isset($order)) {
            $this->invoiceGenerator->generatePDFInvoiceInline($order);
            $this->terminate();
        } else {
            $this->flashMessage('Faktura nebyla nalezena.', 'danger');
            $this->redirect(':Public:Orders:myOrders');
        }
    }

    /**
     * @throws AbortException
     */
    public function actionCreate(): void
    {
        if ($this->cartFacade->isEmptyCart($this->cartSession->get('cartId'))) {
            $this->flashMessage('Nelze vytvořit prázdnou objednávku.', 'danger');
            $this->redirect(':Public:Homepage:default');
        }
    }

    public function renderCreate(): void
    {
        $form = $this->getComponent('createOrderForm');
        if ($this->user->isLoggedIn())
        {
            $form->setDefaults([
                'name' => $this->user->getIdentity()->getData()['name'],
                'email' => $this->user->getIdentity()->getData()['email']
            ]);
        }

        $this->template->cart = $this->cartFacade->getCartById($this->cartSession->get('cartId'));

    }

    public function createComponentCreateOrderForm(): Form
    {
        $onSuccess = function (string $message): void {
            $this->flashMessage($message, 'success');
            $this->redirect(':Public:Homepage:default');
        };

        $onFailure = function (string $message): void {
            $this->flashMessage($message, 'danger');
            $this->redirect('this');
        };
        return $this->createOrderFormFactory->create($onSuccess, $onFailure);
    }

    public function createComponentMyOrdersDataGrid(): MyOrdersDataGridControl
    {
        return $this->myOrdersDataGridControlFactory->create();
    }
}