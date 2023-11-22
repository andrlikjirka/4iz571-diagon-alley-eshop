<?php

declare(strict_types=1);

namespace App\Model\Orm\ForgottenPasswords;

use Nextras\Orm\Entity\Entity;
use App\Model\Orm\Users\User;
use Nextras\Dbal\Utils\DateTimeImmutable;


/**
 * @property int $id {primary}
 * @property User $user {m:1 User::$forgottenPasswords}
 * @property string $code
 * @property DateTimeImmutable $created {default now}
 */
class ForgottenPassword extends Entity
{
}
