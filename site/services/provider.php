<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\Component\Advportfolio\Site\Extension\AdvportfolioComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class implements ServiceProviderInterface {
	public function register(Container $container)
	{
		$container->registerServiceProvider(new MVCFactory('\\Joomla\\Component\\Advportfolio'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomla\\Component\\Advportfolio'));
		$container->registerServiceProvider(new RouterFactory('\\Joomla\\Component\\Advportfolio'));

		$container->set(
			ComponentInterface::class,
			function (Container $container) {
				$component = new AdvportfolioComponent($container->get(ComponentDispatcherFactory::class));
				$component->setMVCFactory($container->get(MVCFactory::class));
				$component->setRouterFactory($container->get(RouterFactory::class));

				return $component;
			}
		);
	}
};