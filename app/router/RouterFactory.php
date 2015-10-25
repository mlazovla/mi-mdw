<?php

namespace App;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

		$admin = new RouteList('Admin');
		$admin[] = new Route('admin/<presenter>/<action>[/<id>]', 'Homepage:default');
		$router[] = $admin;

		$router[] = $frontRouter = new RouteList('Front');
		$frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

		return $router;
	}
}