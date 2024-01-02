<?php

namespace App\PublicModule\Presenters;

use App\Model\Facades\CartFacade;
use App\Model\Orm\Roles\Role;
use App\PublicModule\Forms\CreateOrderFormFactory;
use Nette\Forms\Form;
use Nette\Http\Session;
use Nette\Http\SessionSection;

/**
 * Class OrderPresenter
 * @package App\PublicModule\Presenters
 * @author Jiří Andrlík
 */
class OrderPresenter extends BasePresenter
{
    private SessionSection $cartSession;

    public function __construct(
        private readonly CreateOrderFormFactory $createOrderFormFactory,
        private readonly CartFacade $cartFacade,
        Session $session,


    )
    {
        $this->cartSession = $session->getSection('cart');
        parent::__construct();
    }

    public function actionCreate()
    {
        if ($this->cartFacade->isEmptyCart($this->cartSession->get('cartId'))) {
            $this->flashMessage('Nelze vytvořit prázdnou objednávku.', 'danger');
            $this->redirect(':Public:Homepage:default');
        }
    }

    public function renderCreate()
    {
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
}