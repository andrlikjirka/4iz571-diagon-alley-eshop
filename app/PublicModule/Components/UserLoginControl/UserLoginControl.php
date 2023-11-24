<?php

namespace App\PublicModule\Components\UserLoginControl;

use Nette\Application\UI\Control;
use Nette\Application\UI\Template;
use Nette\Http\Session;
use Nette\Security\User;

class UserLoginControl extends Control
{
    private User $user;

    private Session $session;

    public function __construct(User $user, Session $session)
    {
        $this->user = $user;
        $this->session = $session;
    }

    public function handleLogin(): void
    {
        if ($this->user->isLoggedIn()) {
            $this->presenter->redirect('this');
        } else {
            $this->presenter->redirect(':Public:LogIn:default',['backlink'=>$this->presenter->storeRequest()]);
        }
    }

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

    public function render($params = []): void
    {
        $this->template->user = $this->user;
        $this->template->class = (!empty($params['class']) ? $params['class'] : '');
        $this->template->render(__DIR__ . '/templates/' . 'default.latte');
    }


}