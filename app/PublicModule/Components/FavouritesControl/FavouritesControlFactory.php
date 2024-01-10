<?php

declare(strict_types=1);

namespace App\PublicModule\Components\FavouritesControl;

/**
 * Class FavouritesControlFactory
 * @package App\PublicModule\Components\FavouritesControl
 * @author Martin Kovalski
 */
interface FavouritesControlFactory
{
	public function create(): FavouritesControl;
}