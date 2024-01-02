<?php

namespace App\Model\Facades;

use App\Model\Orm\Orders\Order;
use App\Model\Orm\Orm;
use Exception;
use Nextras\Orm\Entity\IEntity;
use Tracy\Debugger;

class OrdersFacade
{
    /**
     * UsersFacade constructor
     * @param Orm $orm
     */
    public function __construct (
        private readonly Orm $orm
    ){}

    public function saveNewOrder(Order $order): void
    {
        try{
            $this->orm->orders->persistAndFlush($order);
        } catch (Exception $e) {
            Debugger::log($e);
            $this->orm->orders->getMapper()->rollback();
            throw new Exception($e);
        }
    }

    public function getOrderStatusByStatusId(int $statusId): IEntity
    {
        return $this->orm->orderStatus->getByIdChecked($statusId);
    }

}