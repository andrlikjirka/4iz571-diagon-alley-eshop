<?php

namespace App\PublicModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\CartFacade;
use Closure;
use Nette\Forms\Form;
use Nette\Http\Session;
use Nette\Utils\ArrayHash;

class AddProductToCartFormFactory
{
    private Closure $onSuccess;
    private Closure $onFailure;

    public function __construct(
        private readonly FormFactory $formFactory,
        private readonly CartFacade  $cartFacade,
        Session                      $session
    )
    {
        $this->cartSession = $session->getSection('cart');
    }

    public function create(callable $onSuccess, callable $onFailure): Form
    {
        $form = $this->formFactory->create();

        $form->addHidden('productId')
            ->setRequired();

        $form->addInteger('quantity', '')
            ->setRequired()
            ->addRule(Form::INTEGER, 'Počet kusů musí být celé číslo!')
            ->addRule(Form::MIN, 'Počet kusů musí být číslo větší než 1', 1)
            ->setDefaultValue(1);

        $form->addSubmit('addToCart', 'Přidat do košíku');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }

    public function formSucceeded(Form $form, ArrayHash $values): void
    {
        $cart = $this->cartFacade->getCartById($this->cartSession->get('cartId'));
        try {
            $this->cartFacade->addProductToCart($cart, $values['productId'], $values['quantity']);
        } catch (\Exception $e) {
            ($this->onFailure)('Při přidávání produktu do košíku se vyskytla chyba.');
            return;
        }
        ($this->onSuccess)('Produkt byl přidán do košíku.');
    }

}