<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Site
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Include dependencies
require_once __DIR__ . '/helpers/advportfolio.php';
require_once __DIR__ . '/helpers/route.php';
require_once __DIR__ . '/controllers/controller.php';

// Get WebAssetManager
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

// REGISTER site assets
$wa->registerScript('com_advportfolio.script', 'media/js/script.js', [], ['defer' => true]);
$wa->registerStyle('com_advportfolio.style', 'media/css/style.css');

// USE site assets
$wa->useScript('com_advportfolio.script');
$wa->useStyle('com_advportfolio.style');

// Get and execute the controller
$input = Factory::getApplication()->getInput();
$controller = BaseController::getInstance('AdvPortfolio', ['default_view' => 'projects']);
$controller->execute($input->getCmd('task'));
$controller->redirect();