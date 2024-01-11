<?php

namespace App\Model\MailSender;

use App\Model\Orm\Orders\Order;
use App\Model\Orm\Users\User;
use App\Settings;
use Latte\Engine;
use Nette\Mail\Mailer;
use Nette\Mail\Message;

class MailSender
{
    private string $mailFrom;
    private string $nameFrom;

    public function __construct(
        private readonly Mailer $mailer,
        private readonly Engine $latte,
        private readonly Settings $settings
    )
    {
        $this->mailFrom = $this->settings->mail['mailFrom'];
        $this->nameFrom = $this->settings->mail['nameFrom'];
    }

    public function sendForgottenPasswordMail(User $user, string $mailLink): void
    {
        $params = [
            'user' => $user,
            'mailLink' => $mailLink
        ];

        $mail = new Message();
        $mail->setFrom($this->mailFrom, $this->nameFrom);
        $mail->addTo($user->email, $user->name);
        $mail->subject = 'Obnova zapomenutého hesla';
        //$mail->htmlBody = 'Obdrželi jsme vaši žádost na obnovu zapomenutého hesla. Pokud si přejete heslo změnit, <a href="' . $mailLink . '">klikněte zde</a>.';
        $mail->setHtmlBody($this->latte->renderToString(__DIR__ . '/templates/forgottenPasswordMail.latte', $params));
        #endregion příprava textu mailu

        //odeslání mailu pomocí PHP funkce mail
        $this->mailer->send($mail); //případně smtpMailer
    }

    public function sendNewUserMail(User $user, string $mailLink): void
    {
        $params = [
          'user' => $user,
          'mailLink' => $mailLink
        ];

        $mail = new Message();
        $mail->setFrom($this->mailFrom, $this->nameFrom);
        $mail->addTo($user->email, $user->name);
        $mail->subject = 'Nový uživatel eshopu z Příčné ulice';
        //$mail->htmlBody = 'Byl jste přidán jako nový uživatel. Pro nastavení nového hesla klikněte zde:, <a href="' . $mailLink . '">klikněte zde</a>.';
        $mail->setHtmlBody($this->latte->renderToString(__DIR__ . '/templates/newUserMail.latte', $params));
        #endregion příprava textu mailu

        //odeslání mailu pomocí PHP funkce mail
        $this->mailer->send($mail); // případně smtpMailer
    }

    public function sendNewOrderMail(Order $order, User $user): void
    {
        $params = [
            'order' => $order,
            'user' => $user
        ];

        $mail = new Message();
        $mail->setFrom($this->mailFrom, $this->nameFrom);
        $mail->addTo($user->email, $user->name);
        $mail->subject = 'Potvrzení objednávky č. '.$order->id;
        //$mail->htmlBody = 'Byl jste přidán jako nový uživatel. Pro nastavení nového hesla klikněte zde:, <a href="' . $mailLink . '">klikněte zde</a>.';
        $mail->setHtmlBody($this->latte->renderToString(__DIR__ . '/templates/newOrderMail.latte', $params));
        #endregion příprava textu mailu

        //odeslání mailu pomocí PHP funkce mail
        $this->mailer->send($mail); // případně smtpMailer
    }
}