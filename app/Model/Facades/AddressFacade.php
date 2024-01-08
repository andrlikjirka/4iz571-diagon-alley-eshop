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

	/**
	 * @throws Exception
	 * @return IEntity<Address>
	 */
	public function saveAddress(Address $address): IEntity
    {
        try{
            return $this->orm->addresses->persistAndFlush($address);
        } catch (Exception $e) {
            Debugger::log($e);
            $this->orm->addresses->getMapper()->rollback();
            throw new Exception($e);
        }
    }

	public function getAddressesPairsByUserId(int $userId): array
	{
		$collection = $this->orm->addresses->findBy([
			'user->id' => $userId,
			'deleted' => '0'
			]);

		$pairs = [];
		foreach ($collection as $address) {
			$pairs[$address->id] = [
				'addressId' => $address->id,
				'name' => $address->name,
				'street' => $address->street,
				'city' => $address->city,
				'zip' => $address->zip,
			];
		}

		return $pairs;
	}

	public function getAddress($addressId): ?Address
	{
		return $this->orm->addresses->getById($addressId);
	}

	public function deleteAddresses(int $userId, array $notDeletedAddresses): void
	{
		if($notDeletedAddresses) {
			$this->orm->addresses->deleteOldAddresses($userId, $notDeletedAddresses);
		}
	}
}