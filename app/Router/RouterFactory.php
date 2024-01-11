<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$adminRouter = new RouteList('Admin');
		$adminRouter->addRoute('admin/<presenter=Dashboard>/<action=default>[/<id>]');

		$publicRouter = new RouteList('Public');
		$publicRouter->addRoute('sitemap.xml', 'Homepage:sitemap');
		$publicRouter->addRoute('products[/<categorySlug>]', 'Products:default');
		$publicRouter->addRoute('product[/<categorySlug>]/<productSlug>', 'Products:show');

		$publicRouter->addRoute('<presenter=Homepage>/<action=default>[/<id>]');

		$router = new RouteList();

		$router->add($adminRouter);
		$router->add($publicRouter);

		return $router;
	}
}
