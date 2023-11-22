<?php

namespace App\Model\Orm\ForgottenPasswords;

use App\Model\Orm\AbstractEntity;
use App\Model\Orm\Users\Users;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int $forgottenPasswordId
 * @property Users $userId {??? Users::$???}
 * @property string $code
 * @property DateTimeImmutable $created {default now}
 */
class ForgottenPasswords extends AbstractEntity
{
}
