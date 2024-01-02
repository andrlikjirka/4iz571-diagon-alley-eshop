<?php

declare(strict_types=1);

namespace App\Model\Orm\Orders;

use App\Model\Orm\OrderItems\OrderItem;
use App\Model\Orm\OrderStatus\OrderStatus;
use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Addresses\Address;
use App\Model\Orm\Users\User;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property int $id {primary}
 * @property Address $address {m:1 Address, oneSided=true}
 * @property DateTimeImmutable $created {default now}
 * @property ?OrderStatus $orderStatus {m:1 OrderStatus::$orders}
 * @property User $user {m:1 User::$orders}
 * @property string $shipping {enum self::SHIPPING_*}
 * @property string $payment {enum self::PAYMENT_*}
 * @property OneHasMany|OrderItem[] $orderItems {1:m OrderItem::$order}
 * @property int $galleonTotalPrice {default 0}
 * @property int $sickleTotalPrice {default 0}
 * @property int $knutTotalPrice {default 0}
 */
class Order extends Entity
{
	public const SHIPPING_VYZVEDNUTI = 'vyzvednuti';
	public const SHIPPING_ZASILKOVNA = 'bradavice';
	public const SHIPPING_SOVA = 'sova';
	public const PAYMENT_HOTOVOST = 'hotovost';
	public const PAYMENT_BANKA = 'banka';
	public const PAYMENT_KARTA = 'karta';

}
