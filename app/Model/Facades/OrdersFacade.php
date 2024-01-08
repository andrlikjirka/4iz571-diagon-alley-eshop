<?php

namespace App\Model\Facades;

use App\Model\Orm\Orders\Order;
use App\Model\Orm\OrderStatus\OrderStatus;
use App\Model\Orm\Orm;
use App\Model\Orm\Users\User;
use Exception;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Tracy\Debugger;

class OrdersFacade
{
    /**
     * UsersFacade constructor
     * @param Orm $orm
     */
    public function __construct (
        private readonly Orm $orm,
        private readonly UsersFacade $usersFacade,
        private readonly ProductsFacade $productsFacade
    ){}

    public function saveOrder(Order $order): void
    {
        try{
            $this->orm->orders->persistAndFlush($order);
        } catch (Exception $e) {
            Debugger::log($e);
            $this->orm->orders->getMapper()->rollback();
            throw new Exception('Objednávku se nepodařilo uložit.');
        }
    }

    public function getOrderStatusByStatusId(int $statusId): IEntity
    {
        return $this->orm->orderStatus->getByIdChecked($statusId);
    }

    /**
     * @throws Exception
     */
    public function findOrdersByUserId(int $userId): ICollection
    {
        $user = $this->usersFacade->getUser($userId);
        return $this->orm->orders->findBy(['user' => $user]);
    }

    /**
     * @param int $id
     * @return IEntity|Order
     */
    public function getOrderById(int $id): IEntity|Order
    {
        return $this->orm->orders->getByIdChecked($id);
    }

    public function updateOrderedProductsStock(Order $order): void
    {
        foreach ($order->orderItems as $orderItem) {
            $product = $this->productsFacade->getProduct($orderItem->product->id);
            $product->stock -= $orderItem->quantity;
            $this->productsFacade->saveProduct($product);
        }
    }

    public function findAllOrders(): ICollection
    {
        return $this->orm->orders->findAll();
    }

    public function changeOrderStatus(int $orderId, $orderStatus): IEntity|Order
    {
        $order = $this->getOrderById($orderId);
        $order->orderStatus = $this->getOrderStatusByStatusId($orderStatus);
        try {
            $this->saveOrder($order);
        } catch (\Exception $e) {
            throw new Exception('Nepovedlo se změnit stav objednávky.');
        }
        return $order;
    }

    public function findOrderStatusPairs(): array
    {
        return $this->orm->orderStatus->findAll()->fetchPairs('id', 'name');
    }

}