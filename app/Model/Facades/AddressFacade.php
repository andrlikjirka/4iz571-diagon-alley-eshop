<?php

namespace App\Model\Facades;

use App\Model\Orm\Addresses\Address;
use App\Model\Orm\Orm;
use Exception;
use Nextras\Orm\Entity\IEntity;
use Tracy\Debugger;

class AddressFacade
{
    /**
     * UsersFacade constructor
     * @param Orm $orm
     */
    public function __construct (
        private readonly Orm $orm
    ){}

    public function getAddressById(int $addressId): ?IEntity
    {
        return $this->orm->addresses->getByIdChecked($addressId);
    }

    public function saveAddress(Address $address): void
    {
        try{
            $this->orm->addresses->persistAndFlush($address);
        } catch (Exception $e) {
            Debugger::log($e);
            $this->orm->addresses->getMapper()->rollback();
            throw new Exception($e);
        }
    }
}