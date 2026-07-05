<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Table\Table;
use Joomla\Component\Advportfolio\Administrator\Extension\AdvportfolioComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;

return new class implements ServiceProviderInterface {
	public function register(Container $container)
	{
		$container->registerServiceProvider(new MVCFactory('\\Joomla\\Component\\Advportfolio'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomla\\Component\\Advportfolio'));

		$container->set(
			ComponentInterface::class,
			function (Container $container) {
				$component = new AdvportfolioComponent($container->get(ComponentDispatcherFactory::class));
				$component->setMVCFactory($container->get(MVCFactory::class));

				return $component;
			}
		);
	}

	/**
	 * Get credits footer string.
	 * @return	string
	 */
	public static function getFooter()
	{
		return '<p class="sl_copyright"><span class="sl_title">Advanced Portfolio - Version ' . self::getVersion() . '</span> Copyright &copy; 2013 by <strong>Skyline Technology Ltd - <a href="http://extstore.com" target="_blank">http://extstore.com</a></strong></p>';
	}

	/**
	 * Get current version of component.
	 */
	public static function getVersion()
	{
		$table		= Table::getInstance('Extension');
		$table->load(array('name' => 'com_advportfolio'));
		$registry	= new Registry($table->manifest_cache);

		return $registry->get('version');
	}
};