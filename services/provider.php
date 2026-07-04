<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Services
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\CMS\WebAsset\WebAssetManagerInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return [
    new ServiceProviderInterface(
        function (Container $container) {
            $container->registerServiceProvider(new MVCFactory('\AdvPortfolio\'));
            $container->registerServiceProvider(new ComponentDispatcherFactory('\AdvPortfolio\'));
        }
    ),
    new ServiceProviderInterface(
        function (Container $container) {
            $container->set(
                WebAssetManagerInterface::class,
                function (Container $container) {
                    $wa = new WebAssetManager($container->get('Application'));
                    
                    // Register admin assets
                    $wa->registerScript('com_advportfolio.admin.script', 'media/com_advportfolio/js/admin.script.js', [], ['defer' => true]);
                    $wa->registerStyle('com_advportfolio.admin.style', 'media/com_advportfolio/css/admin.style.css');
                    
                    // Register site assets
                    $wa->registerScript('com_advportfolio.script', 'media/com_advportfolio/js/script.js', [], ['defer' => true]);
                    $wa->registerStyle('com_advportfolio.style', 'media/com_advportfolio/css/style.css');
                    
                    return $wa;
                }
            );
        }
    )
];