<?php

namespace App\Model\Facades;

use App\Model\Orm\ForgottenPasswords\ForgottenPassword;
use App\Model\Orm\Orm;
use App\Model\Orm\Users\User;
use App\PublicModule\Presenters\ForgottenPasswordPresenter;
use Exception;
use Nette\InvalidStateException;
use Nette\Utils\Random;
use Tracy\Debugger;

class ForgottenPasswordsFacade
{

    public function __construct(
        private readonly Orm $orm
    )
    {
    }

    /**
     * @throws Exception
     */
    public function createNewForgottenPasswordCode($user): ForgottenPassword
    {
        $forgottenPassword = new ForgottenPassword();
        $forgottenPassword->user = $user;
        $forgottenPassword->code = Random::generate(10);
        $this->saveNewForgottenPasswordCode($forgottenPassword);
        return $forgottenPassword;
    }


    /**
     * @param ForgottenPassword $forgottenPassword
     * @return void
     * @throws Exception
     */
    public function saveNewForgottenPasswordCode(ForgottenPassword $forgottenPassword): void
    {
        try {
            $this->orm->forgottenPasswords->persistAndFlush($forgottenPassword);
        } catch (Exception $e) {
            Debugger::log($e);
            $this->orm->forgottenPasswords->rollback();
            throw new Exception('Uložení kódu pro obnovu hesla selhalo.');
        }

    }

    /**
     * @param int $userId
     * @param string $code
     * @return bool
     */
    public function isValidForgottenPassword(int $userId, string $code): bool
    {
        $this->orm->forgottenPasswords->deleteOldForgottenPasswords();
        try {
            $this->orm->forgottenPasswords->findBy(['user' => $userId, 'code' => $code]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function deleteForgottenPasswordsByUser(int $userId): void
    {
        try {
            $deleteForgottenPasswords = $this->orm->forgottenPasswords->findBy(['user' => $userId]);
            foreach ($deleteForgottenPasswords as $deleteForgottenPassword) {
                $this->orm->remove($deleteForgottenPassword);
            }
            $this->orm->flush();
        } catch (InvalidStateException $e) {
            Debugger::log($e);
            $this->orm->forgottenPasswords->getMapper()->rollback();
            throw new Exception('Mazání kódů pro obnovu hesla selhalo.');
        }
    }

}