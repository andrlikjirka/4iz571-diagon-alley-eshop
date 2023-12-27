<?php

namespace App\Model\Facades;

use App\Model\Orm\CartItems\CartItem;
use App\Model\Orm\Carts\Cart;
use App\Model\Orm\Orm;
use Couchbase\DateRangeSearchFacet;
use Nette\Security\User;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Exception\InvalidStateException;
use Tracy\Debugger;

class CartFacade
{

    public function __construct(
        private readonly Orm $orm,
        private readonly ProductsFacade $productsFacade
    )
    {
    }

    public function getCartById(int $cartId): IEntity
    {
        return $this->orm->carts->getById($cartId);
    }

    public function deleteOldCarts(): void
    {
        try {
            $this->orm->carts->deleteOldCarts();
        } catch (\Exception $e) {
            Debugger::log($e);
            $this->orm->users->getMapper()->rollback();
            //TODO: hláška navenek???
        }
    }

    public function saveCart(Cart $cart): void
    {
        $cart->lastModified = new \DateTime();
        try {
            $this->orm->carts->persistAndFlush($cart);
        } catch (\Exception $e) {
            Debugger::log($e);
            $this->orm->carts->getMapper()->rollback();
            throw new \Exception('Košík se nepodařilo uložit!');
        }
    }

    public function getCartByUser(int $userId): IEntity
    {
        return $this->orm->carts->getByChecked(['user' => $userId]);
    }

    public function deleteCartByUser(int $userId): void
    {
        $cart = $this->getCartByUser($userId);
        $this->orm->carts->remove($cart);
    }

    public function addProductToCart(?Cart $cart, $productId, $quantity): void
    {
        $cartItem = null;
        if (!empty($cart->cartItems)) {
            $cartItem = $this->getCartItemByProductId($cart, $productId);
        }

        if (!$cartItem) {
            $cartItem = new CartItem();
            $cartItem->cart = $cart;
            $cartItem->product = $this->productsFacade->getProduct($productId);
        }

        $cartItem->quantity += $quantity;

        try {
            $this->saveCartItem($cartItem);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->saveCart($cart);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function getCartItemByProductId(Cart $cart, int $productId): ?CartItem
    {
        foreach ($cart->cartItems as $cartItem) {
            if ($cartItem->product->id === $productId) {
                return $cartItem;
            }
        }
        return null;
    }

    private function saveCartItem(?CartItem $cartItem): void
    {
        try {
            $this->orm->cartItems->persistAndFlush($cartItem);
        } catch (\Exception $e) {
            Debugger::log($e);
            $this->orm->cartItems->getMapper()->rollback();
            throw new \Exception('Položku košíku se nepodařilo uložit!');
        }
    }

    public function removeCartItem(CartItem $cartItem): void
    {
        try {
            $this->orm->cartItems->removeAndFlush($cartItem);
        } catch (\Exception $e) {
            Debugger::log($e);
            $this->orm->cartItems->getMapper()->rollback();
            throw new \Exception('Položku košíku se nepodařilo odstranit!');
        }
    }

    public function getCartItem(int $cartItemId): IEntity|CartItem
    {
        return $this->orm->cartItems->getByIdChecked($cartItemId);
    }

    /**
     * @throws \Exception
     */
    public function updateCartItem(int $cartItemId, int $quantity): void
    {
        $cartItem = $this->getCartItem($cartItemId);
        $cartItem->quantity = $quantity;
        $this->saveCartItem($cartItem);
    }

    public function emptyCart(Cart $cart)
    {
        foreach ($cart->cartItems as $cartItem) {
            $this->orm->cartItems->remove($cartItem);
        }
        $this->orm->cartItems->flush();
    }


}