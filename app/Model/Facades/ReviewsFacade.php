<?php

namespace App\Model\Facades;

use App\Model\Orm\Orm;
use App\Model\Orm\Reviews\Review;
use Nette\Tokenizer\Exception;
use Tracy\Debugger;

/**
 * Class ReviewsFacade
 * @package App\Model\Facades
 * @author Jiří Andrlík
 */
class ReviewsFacade
{

    /**
     * Konstruktor
     * @param Orm $orm
     */
    public function __construct(
        private readonly Orm $orm
    )
    {}

    /**
     * Metoda pro uložení nové recenze k produktu
     * @param Review $review
     * @return void
     * @throws Exception
     */
    public function saveReview(Review $review): void
    {
        try {
            $this->orm->persistAndFlush($review);
        } catch (Exception $e) {
            Debugger::log($e);
            $this->orm->products->getMapper()->rollback();
            throw new Exception('Produkt se nepodařilo uložit');
        }
    }


}