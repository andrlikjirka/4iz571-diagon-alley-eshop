<?php

namespace App\PublicModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\ReviewsFacade;
use App\Model\Facades\UsersFacade;
use App\Model\Orm\Reviews\Review;
use Closure;
use Nette\Forms\Form;
use Nette\Security\User;
use Nette\Tokenizer\Exception;
use Nette\Utils\ArrayHash;

/**
 * Class AddReviewFormFactory
 * @package App\PublicModule\Forms
 * @author Jiří Andrlík
 */
class AddReviewFormFactory
{
    private Closure $onSuccess;
    private Closure $onFailure;

    public function __construct(
        private readonly FormFactory $formFactory,
        private readonly ProductsFacade $productsFacade,
        private readonly ReviewsFacade $reviewsFacade,
        private readonly User $user,
        private readonly UsersFacade $usersFacade
    )
    {}

    public function create(callable $onSuccess, callable $onFailure): Form
    {
        $form = $this->formFactory->create();

        $form->addHidden('productId')
            ->setRequired();

        $stars = [
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
        ];
        $form->addSelect('stars', 'Počet hvězdiček', $stars);

        $form->addTextArea('text', 'Recenze', 50, 8);

        $form->addSubmit('submit', 'Odeslat');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }

    public function formSucceeded(Form $form, ArrayHash $values)
    {
        $product = null;
        try {
            $product = $this->productsFacade->getProduct($values['productId']);
        } catch (Exception $e) {
            ($this->onFailure)('K danému produktu nelze přiřadit recenzi.');
            return;
        }

        $review = new Review();
        $review->product = $product;
        $review->stars = $values['stars'];
        $review->text = $values['text'];

        if ($this->user->isLoggedIn()) {
            try {
                $review->user = $this->usersFacade->getUser($this->user->id);
            } catch (\Exception $e) {
                $review->user = null;
            }
        }

        try {
            $this->reviewsFacade->saveReview($review);
        } catch (Exception $e) {
            ($this->onFailure)('Recenzi se nepodařilo uložit.');
            return;
        }

        ($this->onSuccess)('Děkujeme za hodnocení produktu.');
    }

}