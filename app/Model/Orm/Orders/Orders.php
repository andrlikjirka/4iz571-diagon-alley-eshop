<?php

namespace App\Model\Orm\Orders;

use App\Model\Orm\AbstractEntity;
use App\Model\Orm\Addresses\Addresses;
use App\Model\Orm\Users\Users;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int $orderId
 * @property Addresses $adressId {??? Addresses::$???}
 * @property string $status {default self::STATUS_received} {enum self::STATUS_*}
 * @property DateTimeImmutable $created {default now}
 * @property Users $userId {??? Users::$???}
 * @property string $shipping {enum self::SHIPPING_*}
 * @property string $payment {enum self::PAYMENT_*}
 */
class Orders extends AbstractEntity
{
	public const STATUS_RECEIVED = 'RECEIVED';
	public const STATUS_PROCESSING = 'PROCESSING';
	public const STATUS_READY = 'READY';
	public const STATUS_DELIVERED = 'DELIVERED';
	public const SHIPPING_VYZVEDNUTI = 'VYZVEDNUTI';
	public const SHIPPING_ZASILKOVNA = 'ZASILKOVNA';
	public const SHIPPING_POSTA = 'POSTA';
	public const SHIPPING_PPL = 'PPL';
	public const PAYMENT_CASH = 'CASH';
	public const PAYMENT_CARD = 'CARD';
	public const PAYMENT_BANK_TRANSFER = 'BANK_TRANSFER';
}
