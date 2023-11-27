<?php

declare(strict_types=1);

namespace App\Model\Orm\Orders;

use App\Model\Orm\OrderItems\OrderItem;
use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Addresses\Address;
use App\Model\Orm\Users\User;
use Nextras\Dbal\Utils\DateTimeImmutable;


/**
 * @property int $id {primary}
 * @property Address $address {m:1 Address, oneSided=true}
 * @property string $status {default self::STATUS_RECEIVED} {enum self::STATUS_*}
 * @property DateTimeImmutable $created {default now}
 * @property User $user {m:1 User::$orders}
 * @property string $shipping {enum self::SHIPPING_*}
 * @property string $payment {enum self::PAYMENT_*}
 * @property OrderItem[] $orderItems {1:m OrderItem::$order}
 * @property int $galleonTotalPrice {default 0}
 * @property int $sickleTotalPrice {default 0}
 * @property int $knutTotalPrice {default 0}
 */
class Order extends Entity
{
	public const STATUS_RECEIVED = 'RECEIVED';
	public const STATUS_PROCESSING = 'PROCESSING';
	public const STATUS_READY = 'READY';
	public const STATUS_DELIVERED = 'DELIVERED';
	public const STATUS_CANCELLED = 'CANCELLED';
	public const SHIPPING_VYZVEDNUTI = 'VYZVEDNUTI';
	public const SHIPPING_ZASILKOVNA = 'ZASILKOVNA';
	public const SHIPPING_POSTA = 'POSTA';
	public const SHIPPING_PPL = 'PPL';
	public const PAYMENT_CASH = 'CASH';
	public const PAYMENT_CARD = 'CARD';
	public const PAYMENT_BANK_TRANSFER = 'BANK_TRANSFER';
}
