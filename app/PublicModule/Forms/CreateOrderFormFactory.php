<?php

namespace App\PublicModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\AddressFacade;
use App\Model\Facades\CartFacade;
use App\Model\Facades\OrdersFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\UsersFacade;
use App\Model\InvoiceGenerator\InvoiceGenerator;
use App\Model\MailSender\MailSender;
use App\Model\Orm\Addresses\Address;
use App\Model\Orm\OrderItems\OrderItem;
use App\Model\Orm\Orders\Order;
use Closure;
use Nette\Forms\Form;
use Nette\Http\Session;
use Nette\Security\User;
use Nette\Tokenizer\Exception;
use Nette\Utils\ArrayHash;

/**
 * Class CreateOrderFormFactory
 * @package App\PublicModule\Forms
 * @author Jiří Andrlík
 */
class CreateOrderFormFactory
{
    private Closure $onSuccess;
    private Closure $onFailure;

    public function __construct(
        private readonly FormFactory    $formFactory,
        private readonly UsersFacade    $usersFacade,
        private readonly User           $user,
        private readonly CartFacade     $cartFacade,
        private readonly ProductsFacade $productsFacade,
        private readonly AddressFacade $addressFacade,
        private readonly OrdersFacade $ordersFacade,
        Session                         $session,
        private readonly MailSender $mailService,
        private readonly InvoiceGenerator $invoiceGenerator
    )
    {
        $this->cartSession = $session->getSection('cart');
    }

    public function create(callable $onSuccess, callable $onFailure)
    {
        $form = $this->formFactory->create();

        if ($this->user->isLoggedIn()) {
            $userAddresses = $this->usersFacade->findUserAddresses($this->user->id);
            $addresses = array();
            if (!empty($userAddresses)) {
                foreach ($userAddresses as $userAddress) {
                    $addresses[$userAddress->id] = $userAddress->name . "\n" . $userAddress->street . "\n" . $userAddress->city . "\n" . $userAddress->zip;
                }
            }
        }
        $addresses['-1'] = 'Nová adresa';
        //exit(var_dump($addresses));
        $form->addRadioList('addresses', 'Adresa', $addresses)
            ->setRequired();

        $form->addText('addressee', 'Jméno adresáta');
        $form->addText('street', 'Ulice a číslo popisné');
        $form->addText('city', 'Město');
        $form->addText('zip', 'PSČ')
            ->addRule($form::LENGTH, 'PSČ musí obsahovat %d znaků.', 5);


        $form->addText('name', 'Jméno')
            ->setRequired();

        $form->addEmail('email', 'Emailová adresa')
            ->setRequired();

        $shipping = [
            'vyzvednuti' => 'Vyzvednutí v Příčné ulici',
            'bradavice' => 'Doručení do Bradavic',
            'sova' => 'Doručení sovou na adresu'
        ];
        $form->addRadioList('shipping', 'Zvolte způsob doručení', $shipping)
            ->setRequired();

        $payment = [
            'hotovost' => 'Hotově při převzetí',
            'banka' => 'Převodem do Gringottovy banky',
            'karta' => 'Online kouzelnou platební kartou'
        ];
        $form->addRadioList('payment', 'Zvolte způsob platby', $payment)
            ->setRequired();

        $form->addSubmit('submit', 'Odeslat objednávku');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }

    private function formSucceeded(Form $form, ArrayHash $values): void
    {
        $cart = $this->cartFacade->getCartById($this->cartSession->get('cartId'));

        $order = new Order();

        foreach ($cart->cartItems as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->product = $this->productsFacade->getProduct($cartItem->product->id);
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->galleonPrice = $cartItem->product->galleonPrice;
            $orderItem->sicklePrice = $cartItem->product->sicklePrice;
            $orderItem->knutPrice = $cartItem->product->knutPrice;
            $order->orderItems->add($orderItem);
        }

        if ($values['addresses'] == -1 && isset($values['street']) && isset($values['city']) && isset($values['zip'])) {
            $address = new Address();
            $address->name = $values['addressee'];
            $address->street = $values['street'];
            $address->city = $values['city'];
            $address->zip = $values['zip'];
            $address->user = $this->user->isLoggedIn() ? $this->usersFacade->getUser($this->user->id) : null;
            try {
                $this->addressFacade->saveAddress($address);
            } catch (\Exception $e) {
                ($this->onFailure)($e->getMessage());
                return;
            }
        } else {
            $address = $this->addressFacade->getAddressById($values['addresses']);
        }

        $order->orderStatus = $this->ordersFacade->getOrderStatusByStatusId(1);
        $order->user = $this->user->isLoggedIn() ? $this->usersFacade->getUser($this->user->id) : null;
        $order->name = $values['name'];
        $order->email = $values['email'];
        $order->address = $address;
        $order->shipping = $values['shipping'];
        $order->payment = $values['payment'];
        $order->galleonTotalPrice = $cart->totalPrice['galleon'];
        $order->sickleTotalPrice = $cart->totalPrice['sickle'];
        $order->knutTotalPrice = $cart->totalPrice['knut'];

        try {
            $this->ordersFacade->saveOrder($order);
        } catch (\Exception $e) {
            ($this->onFailure)($e->getMessage());
            return;
        }
        $this->ordersFacade->updateOrderedProductsStock($order);
        $this->cartFacade->emptyCart($cart);

        $invoice = $this->invoiceGenerator->generatePDFInvoiceToString($order);
        $this->mailService->sendNewOrderMail($order, $invoice);
        ($this->onSuccess)('Objednávka byla úspěšně odeslána.');
    }

}