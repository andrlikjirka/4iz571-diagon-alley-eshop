<?php

namespace App\Model\MailSender;

use App\Model\Orm\Orders\Order;
use App\Model\Orm\Users\User;
use App\Settings;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\Template;
use Nette\Application\UI\TemplateFactory;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

/**
 * Class MailSender
 * @package App\Model\MailSender
 * @author Jiří Andrlík
 */
class MailSender
{
    private string $mailFrom;
    private string $nameFrom;

    public function __construct(
        private readonly Settings $settings,
        private readonly TemplateFactory $templateFactory,
        private readonly LinkGenerator $linkGenerator,
        private readonly Mailer $mailer
    )
    {
        $this->mailFrom = $this->settings->mail['mailFrom'];
        $this->nameFrom = $this->settings->mail['nameFrom'];
    }

    private function createTemplate(): Template
    {
        $template = $this->templateFactory->createTemplate();
        $template->getLatte()->addProvider('uiControl', $this->linkGenerator);
        return $template;
    }

    private function createMail(string $toEmail, string $toName, string $subject, string $templateFile, array $params): Message
    {
        $template = $this->createTemplate();

        $mail = new Message();
        $mail->setFrom($this->mailFrom, $this->nameFrom);
        $mail->addTo($toEmail, $toName);
        $mail->setSubject($subject);

        $html = $template->renderToString($templateFile, $params);

        $mail->setHtmlBody($html);

        return $mail;
    }

    public function sendForgottenPasswordMail(User $user, string $mailLink): void
    {
        $params = [
            'mailLink' => $mailLink
        ];
        $subject = 'Obnova zapomenutého hesla';
        $templateFile = __DIR__ . '/templates/forgottenPasswordMail.latte';
        $mail = $this->createMail($user->email, $user->name, $subject, $templateFile, $params);

        $this->mailer->send($mail);
    }

    public function sendNewUserMail(User $user, string $mailLink): void
    {
        $params = [
          'user' => $user,
          'mailLink' => $mailLink
        ];

        $subject = 'Nový uživatel eshopu z Příčné ulice';
        $templateFile = __DIR__ . '/templates/newUserMail.latte';
        $mail = $this->createMail($user->email, $user->name, $subject, $templateFile, $params);

        $this->mailer->send($mail);
    }

    public function sendNewOrderMail(Order $order, string $invoice): void
    {
        $params = [
            'order' => $order
        ];

        $subject = 'Potvrzení objednávky č. '.$order->id;
        $templateFile = __DIR__ . '/templates/newOrderMail.latte';
        $mail = $this->createMail($order->email, $order->name, $subject, $templateFile, $params);
        $mail->addAttachment('faktura-'.$order->id.'.pdf', $invoice, 'application/pdf');

        $this->mailer->send($mail);
    }

    public function sendOrderStatusChangeMail(Order $order): void
    {
        $params = [
            'order' => $order
        ];

        $subject = 'Změna stavu objednávky č. '.$order->id;
        $templateFile = __DIR__ . '/templates/changeOrderStatusMail.latte';
        $mail = $this->createMail($order->email, $order->name, $subject, $templateFile, $params);

        $this->mailer->send($mail);
    }
}