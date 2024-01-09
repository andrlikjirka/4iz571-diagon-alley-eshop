<?php

namespace App\PublicModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\CartFacade;
use App\Model\Facades\ProductsFacade;
use Closure;
use Nette\Forms\Form;
use Nette\Http\Session;
use Nette\Utils\ArrayHash;

class AddProductToCartFormFactory
{
    private Closure $onSuccess;
    private Closure $onFailure;

    public function __construct(
        private readonly FormFactory    $formFactory,
        private readonly CartFacade     $cartFacade,
        private readonly ProductsFacade $productsFacade,
        Session                         $session
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
        $form->setHtmlAttribute('class', 'ajax');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }

    public function formSucceeded(Form $form, ArrayHash $values): void
    {
        $cart = $this->cartFacade->getCartById($this->cartSession->get('cartId'));
        $cartItem = $this->cartFacade->getCartItemByProductId($cart, $values['productId']);

        if (($cartItem != null and $this->productsFacade->getProduct($values['productId'])->stock < ($cartItem->quantity + $values['quantity'])) or
            $this->productsFacade->getProduct($values['productId'])->stock < $values['quantity']) {
            ($this->onFailure)('Do košíku nelze přidat více kusů než je skladem.');
            return;
        }

        try {
            $this->cartFacade->addProductToCart($cart, $values['productId'], $values['quantity']);
        } catch (\Exception $e) {
            ($this->onFailure)('Při přidávání produktu do košíku se vyskytla chyba.');
            return;
        }
        ($this->onSuccess)('Produkt byl přidán do košíku.');
    }
}