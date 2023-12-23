<?php

declare(strict_types=1);

namespace App\Model\Orm\Carts;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;

class CartsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [Cart::class];
	}

    public function deleteOldCarts(): void
    {
        $thirtyDaysAgo = new \DateTime();
        $thirtyDaysAgo = $thirtyDaysAgo->modify('-30 days');

        $carts = $this->findBy([
            ICollection::OR,
            [ICollection::AND, 'user' => null, 'lastModified<' => $thirtyDaysAgo],
            [ICollection::AND, 'lastModified<' => $thirtyDaysAgo]
        ]);

        foreach ($carts as $cart) {
            $this->remove($cart);
        }
        $this->flush();
    }
}
