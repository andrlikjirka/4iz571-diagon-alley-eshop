<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\Facades\OrdersFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\UsersFacade;
use App\Model\Orm\Users\User;

/**
 * Class DashboardPresenter
 * @package App\AdminModule\Presenters
 * @author Martin Kovalski
 */
final class DashboardPresenter extends BasePresenter
{
    public function __construct(
        private readonly OrdersFacade $ordersFacade,
        private readonly UsersFacade $usersFacade,
        private readonly ProductsFacade $productsFacade
    )
    {
        parent::__construct();
    }

    public function renderDefault(): void
	{
        $this->template->ordersTotalCount = $this->ordersFacade->findOrdersTotalCount();
        $this->template->customersTotalCount = $this->usersFacade->findCustomersTotalCount();
        $this->template->showedProductsTotalCount = $this->productsFacade->findShowedProductsTotalCount();
        $this->template->totalSales = $this->ordersFacade->getTotalSales();
    }

}