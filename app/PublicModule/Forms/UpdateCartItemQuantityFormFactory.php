<?php

namespace App\PublicModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\CartFacade;
use App\Model\Orm\Carts\Cart;
use Closure;
use Nette\Forms\Form;
use Nette\Utils\ArrayHash;

class UpdateCartItemQuantityFormFactory
{

    private Closure $onSuccess;
    private Closure $onFailure;

    public function __construct(
        private readonly FormFactory $formFactory,
        private readonly CartFacade $cartFacade

    )
    {}

    public function create(callable $onSuccess, callable $onFailure)
    {
        $form = $this->formFactory->create();

        $form->addHidden('cartItemId', 'CartItem')
            ->setRequired();

        $form->addInteger('quantity', '')
            ->setRequired()
            ->addRule(Form::INTEGER, 'Počet kusů musí být celé číslo!')
            ->addRule(Form::MIN, 'Počet kusů musí být číslo větší než 1', 1);

        $form->addSubmit('update', 'Přidat do košíku');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }

    public function formSucceeded(Form $form, ArrayHash $values): void
    {
        $cartItem = $this->cartFacade->getCartItem($values['cartItemId']);

        if ($cartItem->product->stock < $values['quantity']) {
            ($this->onFailure)('Do košíku nelze přidat více kusů než je skladem.');
            return;
        }

        try {
            $this->cartFacade->updateCartItem($values['cartItemId'], $values['quantity']);
        } catch (\Exception $e) {
            ($this->onFailure)('Položku v košíku se nepodařilo aktualizovat.');
            return;
        }
        ($this->onSuccess)('Položka v košíku byla aktualizována.');
    }


}