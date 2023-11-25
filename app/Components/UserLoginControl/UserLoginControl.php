<?php

namespace App\Components\UserLoginControl;

use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Http\Session;
use Nette\Security\User;

/**
 * Class UserLoginControl
 * @package App\PublicModule\Components\UserLoginControl
 * @author Jiří Andrlík
 */
class UserLoginControl extends Control
{

    /**
     * UserLoginControl constructor
     * @param User $user
     * @param Session $session
     */
    public function __construct(
        private readonly User $user,
        private readonly Session $session)
    {}

    /**
     * Signál pro přesměrování na přihlášení uživatele
     * @return void
     * @throws AbortException
     */
    public function handleLogin(): void
    {
        if ($this->user->isLoggedIn()){
            $this->presenter->redirect('this');
        }else{
            $this->presenter->redirect(':Public:Login:default',['backlink'=>$this->presenter->storeRequest()]);
        }
    }

    /**
     * Signál pro odhlášení uživatele
     * @return void
     * @throws AbortException
     */
    public function handleLogout(): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->presenter->redirect('this');
        } else {
            // Destroy session and clear user identity
            $this->session->destroy();
            $this->user->logout(true);

            // Redirect with flash message
            $this->presenter->flashMessage('Uživatel byl úspěšně odhlášen', 'success');
            $this->presenter->redirect(':Public:Homepage:default');
        }
    }

    /**
     * Signál pro přesměrování na registraci uživatele
     * @return void
     * @throws AbortException
     */
    public function handleRegistration(): void
    {
        if ($this->user->isLoggedIn()){
            $this->presenter->redirect('this');
        }else{
            $this->presenter->redirect(':Public:Registration:default', ['backlink' => $this->presenter->storeRequest()]);
        }
    }

    /**
     * Metoda renderující šablonu komponenty
     * @param $params
     * @return void
     */
    public function render($params = []): void
    {
        $this->template->user = $this->user;
        $this->template->class = (!empty($params['class']) ? $params['class'] : '');
        $this->template->render(__DIR__ . '/templates/' . 'default.latte');
    }


}