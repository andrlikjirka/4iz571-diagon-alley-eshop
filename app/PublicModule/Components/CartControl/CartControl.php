<?php

namespace App\PublicModule\Components\CartControl;


use App\Model\Facades\CartFacade;
use App\Model\Facades\UsersFacade;
use App\Model\Orm\CartItems\CartItem;
use App\Model\Orm\Carts\Cart;
use App\Model\Orm\Products\Product;
use App\PublicModule\Components\CartItemControl\CartItemControl;
use App\PublicModule\Components\CartItemControl\CartItemControlFactory;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Security\User;
use stdClass;

/**
 * @property-read Template|stdClass $template
 */
class CartControl extends Control
{
    private SessionSection $cartSession;
    private Cart $cart;

    public function __construct(
        private readonly User        $user,
        private readonly CartFacade  $cartFacade,
        private readonly UsersFacade $usersFacade,
        Session                      $session,
        private readonly CartItemControlFactory $cartItemControlFactory
    )
    {
        $this->cartSession = $session->getSection('cart');
        $this->cart = $this->prepareCart();
    }

    /**
     * Metoda pro přípravu košíku uloženého v DB
     */
    private function prepareCart(): Cart
    {
        $cart = null;
        #region zkusíme najít košík podle ID ze session
        if ($cartId = $this->cartSession->get('cartId')) {
            $cart = $this->cartFacade->getCartById((int)$cartId);
            //zkontrolujeme, jestli tu není košík od předchozího uživatele, nebo se nepřihlásil uživatel s prázdným košíkem (případně ho zahodíme)
            if (!empty($cart)) {
                if (($cart->user || empty($cart->items)) && ($cart->user && ($cart->user->id != $this->user->id || !$this->user->isLoggedIn()))) {
                    $cart = null;
                }
            }
        }
        #endregion zkusíme najít košík podle ID ze session
        #region vyřešíme vazbu košíku na uživatele, případně vytvoříme košík nový
        if ($this->user->isLoggedIn()) {
            if ($cart) {
                //přiřadíme do košíku načteného podle session vazbu na aktuálního uživatele, tj. ukradnu cizí košík
                if ($cart->user && ($cart->user->id !== $this->user->id)) {
                    $this->cartFacade->deleteCartByUser($this->user->id);
                }
                $cart->user = $this->usersFacade->getUser($this->user->id);
                try {
                    $this->cartFacade->saveCart($cart);
                } catch (\Exception $e) {
                    //throw new \Exception('wdwad');
                }
            } else {
                //zkusíme najít košík podle ID uživatele - pokud ho nenajdeme, vytvoříme nový
                try {
                    $cart = $this->cartFacade->getCartByUser($this->user->id);
                } catch (\Exception $e) {
                    $cart = new Cart();
                    $cart->user = $this->usersFacade->getUser($this->user->id);
                    try {
                        $this->cartFacade->saveCart($cart);
                    } catch (\Exception $e) {

                    }
                    $this->deleteOldCarts();
                }
            }
        } elseif (!$cart) {
            $cart = new Cart();
            try {
                $this->cartFacade->saveCart($cart);
            } catch (\Exception $e) {
                //TODO:
            }
            $this->deleteOldCarts();
        }
        #endregion vyřešíme vazbu košíku na uživatele, případně vytvoříme košík nový

        //aktualizujeme ID košíku v session
        $this->cartSession->set('cartId', $cart->id);
        return $cart;
    }

    /**
     * Metoda pro smazání již neplatných košíků z databáze
     * TODO tuto metodu je vhodné volat buď cronem, nebo při nějaké pravidelně se opakující události (ale ne při každém načtení stránky); v tomto ukázkovém kódu ji voláme při přípravě nového košíku, ale určitě by šlo najít i vhodnější místo
     */
    public function deleteOldCarts(): void
    {
        $this->cartFacade->deleteOldCarts();
    }

    public function handleEmptyCart(): void
    {
        $this->cartFacade->emptyCart($this->cart);
        $this->presenter->flashMessage('Košík byl úspěšně vyprázdněn.', 'success');
        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flashes');
            $this->presenter->redrawControl('cart');
            $this->presenter->redrawControl('content');
        } else {
            $this->presenter->redirect('this');
        }
    }

    /**
     * Akce renderující šablonu s odkazem pro zobrazení košíku jako ikony
     */
    public function render(): void
    {
        $this->template->cart = $this->cart;
        $this->template->render(__DIR__ . '/templates/default.latte');
    }

    /**
     * Akce renderující šablonu s odkazem pro celostránkové zobrazení košíku
     * @param array $params = []
     */
    public function renderList(): void
    {
        $this->template->cart = $this->cart;
        $this->template->render(__DIR__ . '/templates/list.latte');
    }


    public function createComponentCartItem(): CartItemControl
    {
        return $this->cartItemControlFactory->create();
    }
}
