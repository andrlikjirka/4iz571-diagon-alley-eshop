<?php

declare(strict_types=1);

namespace App\Model\Orm\ForgottenPasswords;

use Nextras\Orm\Repository\Repository;

class ForgottenPasswordsRepository extends Repository
{
	public static function getEntityClassNames(): array
	{
		return [ForgottenPassword::class];
	}

    public function deleteOldForgottenPasswords(): void
    {
        $oneHourAgo = new \DateTime();
        $oneHourAgo->modify('-1 hour');

        $oldForgottenPasswords = $this->findBy(['created<' => $oneHourAgo]);
        foreach ($oldForgottenPasswords as $oldForgottenPassword) {
            $this->remove($oldForgottenPassword);
        }
        $this->flush();
    }

}
