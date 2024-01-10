<?php

namespace App\Model\MailService;

use App\Model\Orm\Orders\Order;
use App\Model\Orm\Users\User;
use Latte\Engine;
use Nette\Mail\Mailer;
use Nette\Mail\Message;

class MailService
{
    //TODO: tohle by bylo fajn mít jako konstanty v configu
    private string $mailFromEmail = 'andj10@vse.cz';
    private string $mailFromName = 'Diagon Alley eshop';

    public function __construct(
        private readonly Mailer $mailer,
        private readonly Engine $latte
    )
    {
    }

    public function sendForgottenPasswordMail(User $user, string $mailLink)
    {
        $params = [
            'user' => $user,
            'mailLink' => $mailLink
        ];

        $mail = new Message();
        $mail->setFrom($this->mailFromEmail, $this->mailFromName);
        $mail->addTo($user->email, $user->name);
        $mail->subject = 'Obnova zapomenutého hesla';
        //$mail->htmlBody = 'Obdrželi jsme vaši žádost na obnovu zapomenutého hesla. Pokud si přejete heslo změnit, <a href="' . $mailLink . '">klikněte zde</a>.';
        $mail->setHtmlBody($this->latte->renderToString(__DIR__ . '/templates/forgottenPasswordMail.latte', $params));
        #endregion příprava textu mailu

        //odeslání mailu pomocí PHP funkce mail
        $this->mailer->send($mail); //případně smtpMailer
    }

    public function sendNewUserMail(User $user, string $mailLink)
    {
        $params = [
          'user' => $user,
          'mailLink' => $mailLink
        ];

        $mail = new Message();
        $mail->setFrom($this->mailFromEmail, $this->mailFromName);
        $mail->addTo($user->email, $user->name);
        $mail->subject = 'Nový uživatel eshopu z Příčné ulice';
        //$mail->htmlBody = 'Byl jste přidán jako nový uživatel. Pro nastavení nového hesla klikněte zde:, <a href="' . $mailLink . '">klikněte zde</a>.';
        $mail->setHtmlBody($this->latte->renderToString(__DIR__ . '/templates/newUserMail.latte', $params));
        #endregion příprava textu mailu

        //odeslání mailu pomocí PHP funkce mail
        $this->mailer->send($mail); // případně smtpMailer
    }

    public function sendNewOrderMail(Order $order, User $user)
    {
        $params = [
            'order' => $order,
            'user' => $user
        ];

        $mail = new Message();
        $mail->setFrom($this->mailFromEmail, $this->mailFromName);
        $mail->addTo($user->email, $user->name);
        $mail->subject = 'Potvrzení objednávky č. '.$order->id;
        //$mail->htmlBody = 'Byl jste přidán jako nový uživatel. Pro nastavení nového hesla klikněte zde:, <a href="' . $mailLink . '">klikněte zde</a>.';
        $mail->setHtmlBody($this->latte->renderToString(__DIR__ . '/templates/newOrderMail.latte', $params));
        #endregion příprava textu mailu

        //odeslání mailu pomocí PHP funkce mail
        $this->mailer->send($mail); // případně smtpMailer
    }
}