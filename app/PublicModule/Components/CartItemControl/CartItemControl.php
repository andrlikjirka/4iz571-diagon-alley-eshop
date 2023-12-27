<?php

namespace App\PublicModule\Components\CartItemControl;

use App\Model\Facades\CartFacade;
use App\Model\Orm\CartItems\CartItem;
use App\PublicModule\Forms\UpdateCartItemQuantityFormFactory;
use Nette\Application\UI\Control;
use Nette\Forms\Form;

class CartItemControl extends Control
{
    public function __construct(
        private readonly UpdateCartItemQuantityFormFactory $updateCartItemQuantityFormFactory,
        private readonly CartFacade $cartFacade
    )
    {}

    /**
     * @throws \Exception
     */
    public function handleRemoveCartItem(int $cartItemId): void
    {
        $this->cartFacade->removeCartItem($this->cartFacade->getCartItem($cartItemId));
        $this->redirect('this');
    }

    public function render(CartItem $cartItem): void
    {
        $this->template->cartItem = $cartItem;

        $form = $this->getComponent('updateCartItemQuantityForm');
        $form->setDefaults(['cartItemId' => $cartItem->id]);
        $form->setDefaults(['quantity' => $cartItem->quantity]);

        $this->template->render(__DIR__ . '/templates/default.latte');
    }

    public function createComponentUpdateCartItemQuantityForm(): Form
    {
        $onSuccess = function (string $message) {
            $this->presenter->flashMessage($message, 'success');
            $this->presenter->redirect('this');
        };

        $onFailure = function (string $message) {
            $this->presenter->flashMessage($message, 'success');
            $this->presenter->redirect('this');
        };

        return $this->updateCartItemQuantityFormFactory->create($onSuccess, $onFailure);
    }

}